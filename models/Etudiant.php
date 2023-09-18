<?php
require('EtudiantManager.php');
require('Section.php');

class Etudiant
{

    private int $idEtudiant;
    private string $nom, $prenom, $email, $telmobile;
    private DateTime $datenaissance;
    private Section $laSection;

    public function __construct(
        int $id,
        string $surname = 'à définir',
        string $name = 'à définir',
        string $mail = 'à définir',
        string $phone = 'à définir'
    ) {
        if (!isset($id)) {
            throw new Exception("l'ID de l'étudiant n'a pas été donné.");
        }
        $this->idEtudiant = $id;
        $this->nom = $surname;
        $this->prenom = $name;
        $this->email = $mail;
        $this->telmobile = $phone;
    }

    public function GetNom(): string
    {
        return $this->nom;
    }

    public function GetPrenom(): string
    {
        return $this->prenom;
    }

    public function GetMail(): string
    {
        return $this->email;
    }

    public function GetID(): int
    {
        return $this->idEtudiant;
    }

    public function GetDateNaissance(): DateTime
    {
        return $this->datenaissance;
    }

}