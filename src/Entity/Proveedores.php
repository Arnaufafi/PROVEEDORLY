<?php

namespace App\Entity;

use App\Repository\ProveedoresRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProveedoresRepository::class)
 */
class Proveedores
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $correo;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $tipoProveedor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $introduccionBD;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $ultimaModificacionBD;


    public function __construct($nombre,$correo,$telefono,$tipoProveedor,$activo,$introduccionBD,$ultimaModificacionBD) {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->tipoProveedor = $tipoProveedor;
        $this->activo = $activo;
        $this->introduccionBD = $introduccionBD;
        $this->ultimaModificacionBD = $ultimaModificacionBD;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getTipoProveedor(): ?string
    {
        return $this->tipoProveedor;
    }

    public function setTipoProveedor(string $proveedor): self
    {
        $this->tipoProveedor = $proveedor;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getIntroduccionBD(): ?string
    {
        return $this->introduccionBD;
    }

    public function setIntroduccionBD(string $introduccionBD): self
    {
        $this->introduccionDB = $introduccionBD;

        return $this;
    }

    public function getUltimaModificacionBD(): ?string
    {
        return $this->ultimaModificacionBD;
    }

    public function setUltimaModificacionBD(string $ultimaModificacionBD): self
    {
        $this->ultimaModificacionBD = $ultimaModificacionBD;

        return $this;
    }
}
