<?php

namespace App\Controller;

use App\Entity\Proveedores;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface; 

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main", methods={"GET"})
     *
     * @return Response La respuesta HTTP para la página principal.
     */
    public function index()
    {

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

     /**
     * @Route("/nuevoProveedor/", name="nuevo_proveedor", methods={"GET", "POST"})
     *
     * @param Request $request La solicitud HTTP.
     *
     * @return Response La respuesta HTTP después de la creación de un nuevo proveedor renderizando a la página con mensaje de confirmación
     */
    public function nuevoProveedor(Request $request): Response{
        
        if ($request->isMethod('POST')) {
            $nombre = $_POST["Nombre"];

            // Verificar existencia del proveedor, para evitar repetidos
            $proveedorExistente = $this->getDoctrine()
            ->getRepository(Proveedores::class)
            ->findOneBy(['nombre' => $nombre]);

            if ($proveedorExistente) {
                return $this->render('main/editar/editarProveedorRedirec.html.twig', [
                    'proveedorEncontrado' => $proveedorExistente,
             ]);
             }

            $correo = $_POST["Correo"];
            $telefono = $_POST["Telefono"];
            $tipoProveedor = $_POST["Proveedor"];
            $activo = $_POST["Activo"];
            $introduccionDB = date('Y-m-d H:i:s');
            $Proveedores = new Proveedores($nombre, $correo, $telefono, $tipoProveedor, $activo, $introduccionDB, $introduccionDB);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Proveedores);
            $entityManager->flush();
            return $this->render('main/incluir/nuevoProveedorCreado.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }

        return $this->render('main/incluir/nuevoProveedor.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/editarProveedor/", name="buscar_proveedor", methods={"GET", "POST"})
     *
     * @param Request $request La solicitud HTTP.
     *
     * @return Response La respuesta HTTP después de buscar un proveedor por nombre,junto con el objeto proveedor si se encuentra.
     */ 
    public function buscarProveedor(Request $request): Response
    {

        if ($request->isMethod('POST')) {                          // Si la request es metodo POST
            $nombreBusqueda = $request->request->get('Nombre');    // Obtenemos nombre del formulario   
            $proveedorEncontrado = $this->getDoctrine()            // Buscamos si el nombre existe en la base de datos 
                ->getRepository(Proveedores::class)                
                ->findOneBy(['nombre' => $nombreBusqueda]);        // Si no esta variable null
        }
        else{
            $proveedorEncontrado = null;                            // Si no POST ponemos variable null
        }

        return $this->render('main/editar/editarProveedor.html.twig', [
            'proveedorEncontrado' => $proveedorEncontrado,
     ]);
    }


    /**
     * @Route("/editarProveedor/{id}", name="editar_proveedor", methods={"GET", "POST"})
     *
     * @param Request            $request               Solicitud HTTP, en este caso POST
     * @param Proveedores        $proveedorEncontrado   Proveedor encotrado a editar
     * @param EntityManagerInterface $entityManager     EntityManager de Doctrine.
     *
     * @return Response La respuesta HTTP después de editar un proveedor renderizando a la página con mensaje de confirmación
     */
    public function editarProveedor(Request $request, Proveedores $proveedorEncontrado,EntityManagerInterface $entityManager): Response
    {

        $proveedor = $proveedorEncontrado;

        if ($request->isMethod('POST')) {                   // Si la request es metodo POST
            $nombre = $_POST["Nombre"];                     //Obtener datos del formulario
            $correo = $_POST["Correo"];                     
            $telefono = $_POST["Telefono"];
            $tipoProveedor = $_POST["Proveedor"];
            $activo = $_POST["Activo"];
            $ultimaModificacionBD= date('Y-m-d H:i:s');    //Assignamos la fecha del momento de submit a la variable ultima modificación

            $proveedor->setCorreo($correo);                 //Guardamos los cambios usando setters
            $proveedor->setTelefono($telefono);
            $proveedor->setTipoProveedor($tipoProveedor);
            $proveedor->setActivo($activo);
            $proveedor->setUltimaModificacionBD($ultimaModificacionBD);

            $entityManager->flush();                       //Actualizamos la base de datos
            return $this->render('main/editar/editarProveedorCorrecto.html.twig');  //Renderizamos la página con el mensaje de confirmación

        }

    return $this->render('main/editar/editarProveedor.html.twig');
}


    /**
     * @Route("/eliminarProveedor/", name="buscar_proveedorElim", methods={"GET", "POST"})
     *
     * @param Request $request La solicitud HTTP.
     *
     * @return Response La respuesta HTTP después de buscar un proveedor para eliminar, junto con el objeto proveedor si se encuentra.
     */
    public function buscarProveedorElim(Request $request): Response
    {
        if ($request->isMethod('POST')) {                            // Si la request es metodo POST
            $nombreBusqueda = $request->request->get('Nombre');      // Obtenemos nombre del formulario   
            $proveedorEncontrado = $this->getDoctrine()              // Buscamos si el nombre existe en la base de datos   
                ->getRepository(Proveedores::class)                  // Si no esta variable null
                ->findOneBy(['nombre' => $nombreBusqueda]); 
        } else {
            $proveedorEncontrado = null;                            // Si no POST assignamos variable a null
        }

        return $this->render('main/eliminar/eliminarProveedor.html.twig', [
            'proveedorEncontrado' => $proveedorEncontrado,
        ]);
    }

    /**
     * @Route("/eliminarProveedor/{id}", name="eliminar_proveedor", methods={"DELETE", "POST"})
     *
     * @param Request            $request            La solicitud HTTP.
     * @param Proveedores        $proveedorEncontrado El proveedor encontrado para eliminar.
     * @param EntityManagerInterface $entityManager    El EntityManager de Doctrine.
     *
     * @return Response La respuesta HTTP después de eliminar un proveedor renderizando a la página con mensaje de confirmación.
     */
    public function eliminarProveedor(Request $request, Proveedores $proveedorEncontrado,EntityManagerInterface $entityManager): Response
    {   
        $proveedor = $proveedorEncontrado;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($proveedor);
        $entityManager->flush();
        return $this->render('main/eliminar/eliminarProveedorCorrecto.html.twig');
    }



     /**
     * @Route("/proveedores", name="mostrar_proveedores", methods={"GET"})
     *
     * @return Response La respuesta HTTP después de mostrar todos los proveedores con los datos necesarios para imprimir la tabla.
     */
    public function mostrarProveedores(): Response
    {
        $proveedores = $this->getDoctrine()->getRepository(Proveedores::class)->findAll();

        return $this->render('main/mostrar/mostrarProveedores.html.twig', [
            'proveedores' => $proveedores,
        ]);
    }
    
}