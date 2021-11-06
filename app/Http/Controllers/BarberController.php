<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\BarberAvailability;
use App\Models\BarberPhoto;
use App\Models\BarberService;
use App\Models\BarberTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BarberController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');

        $this->loggedUser = Auth::user();
    }

    public function createRandom()
    {
        $array = ['error' => ''];
        for ($q = 0; $q < 15; $q++) {
            $names = ['Boniek', 'Paulo', 'Pedro', 'Amanda', 'Leticia', 'Gabriel', 'Gabriela', 'Thais', 'Luiz', 'Diogo', 'José', 'Jeremias', 'Francisco', 'Dirce', 'Marcelo'];
            $lastnames = ['Santos', 'Silva', 'Santos', 'Silva', 'Alvaro', 'Sousa', 'Diniz', 'Josefa', 'Luiz', 'Diogo', 'Limoeiro', 'Santos', 'Limiro', 'Nazare', 'Mimoza'];
            $services = ['Corte', 'Pintura', 'Aparação', 'Unha', 'Progressiva', 'Limpeza de Pele', 'Corte Feminino'];
            $services2 = ['Cabelo', 'Unha', 'Pernas', 'Pernas', 'Progressiva', 'Limpeza de Pele', 'Corte Feminino'];
            $testimonials = [
                'Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate consequatur tenetur facere voluptatibus iusto accusantium vero sunt, itaque nisi esse ad temporibus a rerum aperiam cum quaerat quae quasi unde.',
                'Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate consequatur tenetur facere voluptatibus iusto accusantium vero sunt, itaque nisi esse ad temporibus a rerum aperiam cum quaerat quae quasi unde.',
                'Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate consequatur tenetur facere voluptatibus iusto accusantium vero sunt, itaque nisi esse ad temporibus a rerum aperiam cum quaerat quae quasi unde.',
                'Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate consequatur tenetur facere voluptatibus iusto accusantium vero sunt, itaque nisi esse ad temporibus a rerum aperiam cum quaerat quae quasi unde.',
                'Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate consequatur tenetur facere voluptatibus iusto accusantium vero sunt, itaque nisi esse ad temporibus a rerum aperiam cum quaerat quae quasi unde.'
            ];
            $newBarber = new Barber();
            $newBarber->name = $names[rand(0, count($names) - 1)] . ' ' . $lastnames[rand(0, count($lastnames) - 1)];
            $newBarber->avatar = rand(1, 4) . '.png';
            $newBarber->stars = rand(2, 4) . '.' . rand(0, 9);
            $newBarber->latitude = '-23.5' . rand(0, 9) . '30907';
            $newBarber->longitude = '-46.6' . rand(0, 9) . '82759';
            $newBarber->save();
            $ns = rand(3, 6);
            for ($w = 0; $w < 4; $w++) {
                $newBarberPhoto = new BarberPhoto();
                $newBarberPhoto->barber_id = $newBarber->id;
                $newBarberPhoto->url = rand(1, 5) . '.png';
                $newBarberPhoto->save();
            }
            for ($w = 0; $w < $ns; $w++) {
                $newBarberService = new BarberService();
                $newBarberService->barber_id = $newBarber->id;
                $newBarberService->name = $services[rand(0, count($services) - 1)] . ' de ' . $services2[rand(0, count($services2) - 1)];
                $newBarberService->price = rand(1, 99) . '.' . rand(0, 100);
                $newBarberService->save();
            }
            for ($w = 0; $w < 3; $w++) {
                $newBarberTestimonial = new BarberTestimonial();
                $newBarberTestimonial->barber_id = $newBarber->id;
                $newBarberTestimonial->name = $names[rand(0, count($names) - 1)];
                $newBarberTestimonial->rate = rand(2, 4) . '.' . rand(0, 9);
                $newBarberTestimonial->body = $testimonials[rand(0, count($testimonials) - 1)];
                $newBarberTestimonial->save();
            }
            for ($e = 0; $e < 4; $e++) {
                $rAdd = rand(7, 10);
                $hours = [];
                for ($r = 0; $r < 8; $r++) {
                    $time = $r + $rAdd;
                    if ($time < 10) {
                        $time = '0' . $time;
                    }
                    $hours[] = $time . ':00';
                }
                $newBarberAvail = new BarberAvailability();
                $newBarberAvail->barber_id = $newBarber->id;
                $newBarberAvail->week_day = $e;
                $newBarberAvail->hours = implode(',', $hours);
                $newBarberAvail->save();
            }
        }
        return $array;
    }
}
