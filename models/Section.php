<?php
class Section
{

    private int $idSection;
    private string $nomSection;

    public function __construct(int $id, string $name)
    {
        if (isset($id) && !empty($id)) {
            $this->idSection = $id;
        } else {
            throw new Exception("l'ID ne peut pas être vide");
        }
        if (isset($name) && !empty($name)) {
            $this->nomSection = $name;
        } else {
            throw new Exception("le nom ne peut pas être vide");
        }
    }

    public function GetNom(): string
    {
        return $this->nomSection;
    }

    public function GetId(): int
    {
        return $this->idSection;
    }



}