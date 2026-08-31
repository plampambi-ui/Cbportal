<?php

namespace App;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ProjectStatus: int implements HasLabel, HasColor, HasIcon
{
    case NotStarted = 1;
    case InProgress = 2;
    case OnHold = 3;
    case Cancelled = 4;
    case Completed = 5;

    /**
     * Get the human-readable label for Filament UI and localized views.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::NotStarted => __('Not Started'),
            self::InProgress => __('In Progress'),
            self::OnHold     => __('On Hold'),
            self::Cancelled  => __('Cancelled'),
            self::Completed  => __('Completed'),
        };
    }

    /**
     * Get the Filament color token or array representation for badges and tables.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NotStarted => Color::Gray,
            self::InProgress => Color::Blue,
            self::OnHold     => Color::Amber,
            self::Cancelled  => Color::Red,
            self::Completed  => Color::Emerald,
        };
    }

    /**
     * Get the Heroicon name for Filament tables, forms, and badges.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::NotStarted => 'heroicon-o-clock',
            self::InProgress => 'heroicon-o-arrow-path',
            self::OnHold     => 'heroicon-o-pause-circle',
            self::Cancelled  => 'heroicon-o-x-circle',
            self::Completed  => 'heroicon-o-check-circle',
        };
    }

    /**
     * Business Logic Helper: Check if the status represents an active project workflow.
     */
    public function isActive(): bool
    {
        return match ($this) {
            self::InProgress, self::OnHold => true,
            default => false,
        };
    }

    /**
     * Business Logic Helper: Check if the project is in a finalized state.
     */
    public function isFinalized(): bool
    {
        return match ($this) {
            self::Completed, self::Cancelled => true,
            default => false,
        };
    }

    /**
     * State Machine Helper: Determine allowed status transitions from current state.
     * 
     * @return array<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::NotStarted => [self::InProgress, self::Cancelled],
            self::InProgress => [self::OnHold, self::Completed, self::Cancelled],
            self::OnHold     => [self::InProgress, self::Cancelled],
            self::Cancelled  => [self::NotStarted], // Allow reopening
            self::Completed  => [self::InProgress],  // Allow reopening
        };
    }

    /**
     * Validation Helper: Check if transitioning to a given target status is allowed.
     */
    public function canTransitionTo(self $targetStatus): bool
    {
        return in_array($targetStatus, $this->allowedTransitions(), true);
    }
}