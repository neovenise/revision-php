<?php
require('Section.php');

class Etudiant
{

    private int $idEtudiant;
    private string $nom, $prenom, $email, $telmobile;
    private DateTime $datenaissance;
    private Section $section;

    /**
     * Constructeur de la classe Etudiant
     * 
     * @param int $id id de l'étudiant
     * @param string $surname nom de l'étudiant
     * @param string $name prénom de l'étudiant
     * @param string $mail mail de l'étudiant
     * @param string $phone numéro de téléphone de l'étudiant
     * @param DateTime $date Date de naissance de l'étudiant
     * @param Section $laSection Section de l'étudiant
     * @throws Exception Est retourné si l'id de l'étudiant n'est pas donné. (?)
     */
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

    /**
     * Accesseur du nom de l'étudiant
     * @return string nom de l'étudiant
     */
    public function GetNom(): string
    {
        return $this->nom;
    }

    /**
     * Accesseur du prénom de l'étudiant
     * @return string prénom de l'étudiant
     */
    public function GetPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * Accesseur du mail de l'étudiant
     * @return string mail de l'étudiant
     */
    public function GetMail(): string
    {
        return $this->email;
    }

    /**
     * Accesseur de l'ID de l'étudiant
     * @return int id de l'étudiant
     */
    public function GetID(): int
    {
        return $this->idEtudiant;
    }

    /**
     * Accesseur de la date de naissance de l'étudiant
     * @return DateTime date de naissance de l'étudiant
     */
    public function GetDateNaissance(): DateTime
    {
        return $this->datenaissance;
    }

}