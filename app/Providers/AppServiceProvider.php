<?php

namespace App\Providers;

use App\Models\FollowupCheckup;
use App\Models\Treatment;
use App\Models\User;
use Filament\Notifications\DatabaseNotification;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {
            return $user->isSuperAdmin() ? true: null;
        });




        $notif = DB::table('notifications')
            ->where('type', DatabaseNotification::class)
            ->whereDate('created_at', '=', now('Asia/Manila')->toDateString())
            ->exists();

        $followupCheckupToday = FollowupCheckup::whereDate('followupDate', now()->toDateString())
                                ->where('followupStatus', 0)
                                ->count();

        $followupCheckupLapses = FollowupCheckup::whereDate('followupDate','<', now()->toDateString())->where('followupStatus', 0);
        if($followupCheckupLapses->count() > 0) {
            $records = $followupCheckupLapses->get();
            $records->each(function ($record){
                $record->followupStatus = 2;
                $record->save();
            });
        }





        if ($followupCheckupToday>0) {
            if (!$notif) {
                $conjunc = ($followupCheckupToday >= 2) ? "are": "is";

                Notification::make()
                    ->title('Expected patient today('.now()->format('M d, Y').')')
                    ->body("There {$conjunc} {$followupCheckupToday} patient to follow for a checkup.")
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('markAsRead')
                            ->button()
                            ->outlined()
                            ->markAsRead(),
                    ])
                    ->sendToDatabase(\App\Models\User::find(1));

            }
        }










    }
}
