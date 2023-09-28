<?php
require('Section.php');

class Etudiant
{

    private int $idEtudiant;
    private string $nom, $prenom, $email, $telmobile;
    private DateTime $datenaissance;
    private Section $section;

    public function __construct(
        int $id,
        string $surname,
        string $name,
        string $mail,
        string $phone,
        DateTime $date,
        Section $laSection
    ) {
        if (!isset($id)) {
            throw new Exception("l'ID de l'étudiant n'a pas été donné.");
        }
        $this->idEtudiant = $id;
        $this->nom = $surname;
        $this->prenom = $name;
        $this->email = $mail;
        $this->telmobile = $phone;
        $this->datenaissance = $date;
        $this->section = $laSection;
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