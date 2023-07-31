<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'construire-main',
    description: 'Add a short description for your command',
    hidden: false,
    aliases: ['app:construire-main'],
)]
class ConstruireMainCommand extends Command
{
    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('jeu de cartes...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln([
            'Le test technique consiste à développer un jeu de cartes en utilisant PHP/Symfony, où un joueur tire 
             une main de 10 cartes de manière aléatoire, avec des couleurs et des valeurs définies aléatoirement, 
             puis présente la main "non triée" à l\'écran, suivie de la main triée selon un ordre aléatoire 
             défini pour les couleurs et les valeurs.',
            '============',
            '',
        ]);


        echo "Main non triée :\n";
        $main = $this->genererMainAleatoire($this->genererOrdreAleatoireCouleurs(), $this->genererOrdreAleatoireValeurs());
        foreach ($main as $carte) {
            $output->write( $carte['couleur'] . ' - ' . $carte['valeur'] . "\n");
        }

        echo "\nMain triée :\n";
        $mainTriee = $this->genererMainTriee($main, $this->genererOrdreAleatoireCouleurs(), $this->genererOrdreAleatoireValeurs());
        foreach ($mainTriee as $carte) {
            $output->write( $carte['couleur'] . ' - ' . $carte['valeur'] . "\n");
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    private function genererOrdreAleatoireCouleurs(): array {
        $couleurs = ['Carreaux', 'Coeur', 'Pique', 'Trèfle'];
        shuffle($couleurs);
        return $couleurs;
    }
    private function genererOrdreAleatoireValeurs(): array {
        $valeurs = ['As', '5', '10', '8', '6', '7', '4', '2', '3', '9', 'Dame', 'Roi', 'Valet'];
        shuffle($valeurs);
        return $valeurs;
    }

    private function genererMainAleatoire(array $couleurs, array $valeurs): array {
        $main = [];
        for ($i = 0; $i < 10; $i++) {
            $couleur = $couleurs[array_rand($couleurs)];
            $valeur = $valeurs[array_rand($valeurs)];
            $carte = ['couleur' => $couleur, 'valeur' => $valeur];
            $main[] = $carte;
        }
        return $main;
    }



    private function genererMainTriee(array $main, array $couleurs, array $valeurs): array {
        usort($main, function ($carte1, $carte2) use ($couleurs, $valeurs) {
            $couleur1 = array_search($carte1['couleur'], $couleurs);
            $couleur2 = array_search($carte2['couleur'], $couleurs);
            if ($couleur1 !== $couleur2) {
                return $couleur1 - $couleur2;
            }

            $valeur1 = array_search($carte1['valeur'], $valeurs);
            $valeur2 = array_search($carte2['valeur'], $valeurs);
            return $valeur1 - $valeur2;
        });
        return $main;
    }
}
