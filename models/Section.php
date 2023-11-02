<?php

/**
 * Classe Section
 * Représente une section d'étudiants.
 */
class Section
{

    private int $idSection;
    private string $libelleSection;

    /**
     * Constructeur de la classe Section.
     *
     * @param int $id L'identifiant de la section.
     * @param string $name Le libellé de la section.
     * @throws Exception Si l'ID ou le nom est vide.
     */
    public function __construct(int $id, string $name)
    {
        if (isset($id) && !empty($id)) {
            $this->idSection = $id;
        } else {
            throw new Exception("l'ID ne peut pas être vide");
        }
        if (isset($name) && !empty($name)) {
            $this->libelleSection = $name;
        } else {
            throw new Exception("le nom ne peut pas être vide");
        }
    }

    /**
     * Obtient le libellé de la section.
     *
     * @return string Le libellé de la section.
     */
    public function GetLibelle(): string
    {
        return $this->libelleSection;
    }

    /**
     * Obtient l'identifiant de la section.
     *
     * @return int L'identifiant de la section.
     */
    public function GetId(): int
    {
        return $this->idSection;
    }



}