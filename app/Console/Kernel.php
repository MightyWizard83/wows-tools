<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->call(function () {
//            DB::table('recent_users')->delete();
//        })->daily();
        
        $schedule->command('update_ratings')->withoutOverlapping(5)->daily();
        
        $usersToSync = array(
            526994012, //francesile
            529547158, //Cor3y
            522720889, //MightyWizard
            500957006, //Guaro90
            539777491, //Daytona_Rider
            555150043, //ITALIANEZZ
            518468715, //Gartesh
            505159414, //Daniexpert
            539760504, //Vaporetto_Alley
            542687234, //Feadae
            534973324, //PhisicsLollo
            500710158, //Puls4r_
            517389982, //Foxs_Shadow
            504477855, //MightyRan
            527376163, //_V3NOM
            516193576, //FIow3rPow3r
            531919529, //GRILLETTO_1991
            501078579, //ZapBrannigan
            500530613, //Matthias46
            555392569, //Region116
            535365309, //Comets10
            540200127, //Clear_Look
            500521030, //Vulcan
            501433162, //maxmega
            555247886, //KALlPSO
            520017117, //1v1_99
            501469666, //Setepijotesiluro
            511345123, //TheWhiteJack
            500015973, //antdl
            530528614, //IlCanta
            534312041, //Therius
            500741745, //trinciamosche
            528687358, //Gioik
            500804089, //Loozer
            532821630, //FrancescFabregas
            
            529217864,  //CapitanoAraym
            529926450, //Luckycanna
            502241740, //thekingofsky
            509223888, //SovietRuss
            531445462, //Ghortan
            524181475, //klamony
            508998659, //LEOPARD184
            532945156, //Ermy56
            529277064, //LucasTheOne
            531480333, //Merchenan
            508969877, //Ir_Passe
            542369430, //AdrenSnyder
            529260567, //Pocioloso
            547143837, //ACR_il_Pirata
            504611103, //Doomx87
            504836131, //ilbosko
            502224036, //Lorak_Vanadius
            501582245, //KVETH
            552893607, //Robster_RXD
            501247528, //Bicio
            504151721, //Grigor86
            555273521, //ITALIANST
            548660019, //M51Vortice
            512429236, //Eaasyy
            533900085, //Raamiel79
            542001977, //_Peterbilt_
            514799547, //LatinNapoleon
            529939775, //Mellin90
            500006006, //XLeonidaX
            501766387, //Federico_II
            532845045, //Zintio
            500433487, //MaximusDecimo
            503667430, //lupodelletrofie
            529357285, //MrVombat
            512937185, //Viura46
            511871586, //Lestat01
            531460069, //XTormentoX
            547235686, //AlexCadiem
            509949544, //efrem76
            529589870, //Zebuleb
            537154408, //Manga_joker
            500276083, //Simba01
            535028597, //Aronemepar
            535800566, //Sargath444
            529240166, //bells98
            
            );
        foreach ($usersToSync as $account_id) {
            $schedule->command('WgApi:SyncPlayer '.$account_id)->withoutOverlapping(50)->everyTenMinutes();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
