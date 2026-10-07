<?php

namespace App\Policies;

use App\Models\StudentActivity;
use App\Models\User;

class StudentActivityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'admin',
            'wali kelas',
            'guru mata pelajaran',
            'orang tua',
            'siswa',
        ]);
    }

    public function view(User $user, StudentActivity $studentActivity): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('wali kelas')) {
            return $studentActivity->student
                ->schoolClass
                ->homeroom_teacher_id === $user->id;
        }

        if ($user->hasRole('guru mata pelajaran')) {
            return true;
        }

        if ($user->hasRole('orang tua')) {
            $parentProfile = $user->parentProfile;

            if (! $parentProfile) {
                return false;
            }

            return $parentProfile->students()
                ->where('students.id', $studentActivity->student_id)
                ->exists();
        }

        if ($user->hasRole('siswa')) {
            return $studentActivity->student
                ->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'admin',
            'wali kelas',
        ]);
    }

    public function update(User $user, StudentActivity $studentActivity): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('wali kelas')) {
            return $studentActivity->student
                ->schoolClass
                ->homeroom_teacher_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, StudentActivity $studentActivity): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('wali kelas')) {
            return $studentActivity->student
                ->schoolClass
                ->homeroom_teacher_id === $user->id;
        }

        return false;
    }

    public function restore(User $user, StudentActivity $studentActivity): bool
    {
        return false;
    }

    public function forceDelete(User $user, StudentActivity $studentActivity): bool
    {
        return false;
    }
}
