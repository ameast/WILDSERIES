<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class Fixtures extends Fixture
{
    // Category
    public const CATEGORIES = [
        'Action',
        'Aventure',
        'Animation',
        'Fantastique',
        'Horreur',

    ];

    // Program
    public const PROGRAMS = [
        [
            'title' => 'The Walking Dead',
            'synopsis' => 'Des zombies envahissent la terre.',
            'poster' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRWXm18S0ymVL5-ioJme84jAez0aSmiiUyzw&usqp=CAU',
            'category' => 'Horreur',
            'year' => 2021,
            'country' => 'USA',
        ],
        [
            'title' => 'La Reine Charlotte',
            'synopsis' => "Promise au Roi d'Angleterre contre son gré, Charlotte arrive à Londres et découvre que la famille royale n'est pas ce qu'elle imaginait.",
            'poster' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRWXm18S0ymVL5-ioJme84jAez0aSmiiUyzw&usqp=CAU',
            'category' => 'Drame',
            'year' => 2021,
            'country' => 'USA',
        ],
        [
            'title' => 'The Mandalorian',
            'synopsis' => "Le Mandalorien se situe après la chute de l'Empire et avant l'émergence du Premier Ordre.",
            'poster' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRWXm18S0ymVL5-ioJme84jAez0aSmiiUyzw&usqp=CAU',
            'category' => 'Fantastique',
            'year' => 2021,
            'country' => 'USA',
        ],
        [
            'title' => 'Firefly Lane',
            'synopsis' => "Sur trente ans, les hauts et les bas de Kate et Tully qui, depuis l'adolescence, sont meilleures amies et se soutiennent dans les bons comme les mauvais moments.",
            'poster' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRWXm18S0ymVL5-ioJme84jAez0aSmiiUyzw&usqp=CAU',
            'category' => 'Drame',
            'year' => 2020,
            'country' => 'USA',
        ],
        [
            'title' => 'The Boys',
            'synopsis' => "Le monde est rempli de super-héros qui sont gérés par la société Vought International. Elle s'occupe de leur promotion et leur commercialisation. Ils ne sont pas tous héroïques et parfaits.",
            'poster' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRWXm18S0ymVL5-ioJme84jAez0aSmiiUyzw&usqp=CAU',
            'category' => 'Fantastique',
            'year' => 2019,
            'country' => 'USA',
        ],
        [
            'title' => 'Arcane',
            'synopsis' => "Championnes de leurs villes jumelles et rivales (la huppée Piltover et la sous-terraine Zaun), deux sœurs Vi et Powder se battent dans une guerre où font rage des technologies magiques et des perspectives diamétralement opposées.",
            'poster' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRWXm18S0ymVL5-ioJme84jAez0aSmiiUyzw&usqp=CAU',
            'category' => 'Animation',
            'year' => 2021,
            'country' => 'USA',
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //Category
        $categories = [];

        foreach (self::CATEGORIES as $data) {
            $category = new Category();
            $category->setName($data);
            $manager->persist($category);

            $categories[] = $category;
        }

        // Program
        $programs = [];
        foreach (self::PROGRAMS as $data) {
            $program = new Program();
            $program->setTitle($data['title']);
            $program->setSynopsis($data['synopsis']);
            $program->setPoster($data['poster']);
            $program->setCategory($categories[rand(0, count($categories) - 1)]);

            $manager->persist($program);

            $programs[] = $program;
        }


        // Season
        $seasons = [];

        foreach ($programs as $program)
        {
            for($i = 1; $i < 6; $i++) {
                $season = new Season();
                $season->setSeasonNumber($i);
                $season->setDescription($faker->paragraph(4));
                $season->setYear($faker->numberBetween(1989, 2023));
                // This step is before multiple season by program
                // There's no foreach around season before this step
                // $season->setProgram($programs[rand(0, count($programs) - 1)]);
                $season->setProgram($program);

                $manager->persist($season);

                $seasons[] = $season;
            }
        }

        //Episode
        foreach ($seasons as $season) {
            for ($i = 1; $i < 11; $i++) {
                $episode = new Episode();
                $episode->setTitle($faker->name);
                $episode->setSynopsis($faker->paragraph(2));
                $episode->setEpisodeNumber($i);
                $episode->setSeason($season);

                $manager->persist($episode);
            }
        }


        $manager->flush();
    }
}