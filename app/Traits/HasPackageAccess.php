<?php

namespace App\Traits;

trait HasPackageAccess
{
    public function tierFeatures(): array
    {
        return match ($this->package_tier) {
            'standard' => ['community_access', 'newsfeed_access', 'forum_access'],
            'approved' => ['community_access', 'newsfeed_access', 'forum_access','resources_access', 'directory_listing'],
            'premium' => ['community_access', 'newsfeed_access', 'forum_access','resources_access', 'directory_listing', 'events_access', 'learning_access', 'host_events'],
            default => []
        };
    }

    public function hasAccess(string $feature): bool
    {
        if(!$this->package_tier || $this->package_tier === 'none') {
            return false;
        }
        if ($this->package_expires_at && now()->gte($this->package_expires_at)) {
            return false;
        }

        return in_array($feature, $this->tierFeatures());
    }
}
