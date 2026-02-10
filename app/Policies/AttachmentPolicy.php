<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;

class AttachmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view attachments
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Attachment $attachment): bool
    {
        // All authenticated users can view individual attachments
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin, Almacenista, Jefe de Obra, Tecnico can upload attachments
        return $user->hasAnyRole(['admin', 'almacenista', 'jefe_obra', 'tecnico']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Attachment $attachment): bool
    {
        // Admin, Almacenista can update any attachment
        if ($user->hasAnyRole(['admin', 'almacenista'])) {
            return true;
        }

        // Users can update their own attachments
        return $user->id === $attachment->uploader_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Attachment $attachment): bool
    {
        // Only Admin and Almacenista can delete attachments
        if ($user->hasAnyRole(['admin', 'almacenista'])) {
            return true;
        }

        // Users can delete their own attachments if they uploaded them recently (within 24 hours)
        if ($user->id === $attachment->uploader_id) {
            return $attachment->created_at->diffInHours(now()) < 24;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Attachment $attachment): bool
    {
        // Only Admin can restore deleted attachments
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Attachment $attachment): bool
    {
        // Only Admin can force delete attachments
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can download the attachment.
     */
    public function download(User $user, Attachment $attachment): bool
    {
        // All authenticated users can download attachments
        return true;
    }
}
