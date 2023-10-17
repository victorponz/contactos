<?php

namespace App\Controller;

use App\Entity\Contacto;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use LDAP\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactoController extends AbstractController
{
    
    private $contactos = [
        1 => ["nombre" => "Juan Pérez", "telefono" => "524142432", "email" => "juanp@ieselcaminas.org"],
        2 => ["nombre" => "Ana López", "telefono" => "58958448", "email" => "anita@ieselcaminas.org"],
        5 => ["nombre" => "Mario Montero", "telefono" => "5326824", "email" => "mario.mont@ieselcaminas.org"],
        7 => ["nombre" => "Laura Martínez", "telefono" => "42898966", "email" => "lm2000@ieselcaminas.org"],
        9 => ["nombre" => "Nora Jover", "telefono" => "54565859", "email" => "norajover@ieselcaminas.org"]
    ];   
    #[Route('/contacto/insertar', name: 'insertar')]
    public function insertar(ManagerRegistry $doctrine): Response
    {   
        $entityManager = $doctrine->getManager();
        foreach($this->contactos as $c){
            $contacto = new Contacto();
            $contacto->setNombre($c["nombre"]);
            $contacto->setTelefono($c["telefono"]);
            $contacto->setEmail($c["email"]);
            $entityManager->persist($contacto);
        }
        try{
            $entityManager->flush();
            return new Response("Contactos insertados");
        }catch(Exception $e){
            return new Response("Se ha producido un error " . 
            $e->getMessage());
        }

    }

    #[Route('/contacto/{codigo}', name: 'ficha_contacto')]
    public function index(ManagerRegistry $doctrine, int $codigo): Response
    {
        $repositorio = $doctrine->getRepository(Contacto::class);

        $contacto = $repositorio->find($codigo);
        
        return $this->render('ficha_contacto.html.twig', [
                'contacto' => $contacto,
            ]);       
        
    }
    #[Route('/contacto/updade/{codigo}/{nombre}', name: 'update_contacto')]
    public function update(ManagerRegistry $doctrine, int $codigo, string $nombre): Response
    {
        $repositorio = $doctrine->getRepository(Contacto::class);

        $contacto = $repositorio->find($codigo);
        if (!empty($contacto)){
            $contacto->setNombre($nombre);
            $manager = $doctrine->getManager();
            try{
                $manager->persist($contacto);
                $manager->flush();
            }catch(Exception $e)
            {
                return new Response("Error " . $e->getMessage());
            }
        }
        
        return $this->render('ficha_contacto.html.twig', [
                'contacto' => $contacto,
            ]);       
        
    }
    
    #[Route('/contacto/delete/{codigo}', name: 'delete_contacto')]
    public function delete(ManagerRegistry $doctrine, int $codigo): Response
    {
        $repositorio = $doctrine->getRepository(Contacto::class);

        $contacto = $repositorio->find($codigo);
        if (!empty($contacto)){
            $manager = $doctrine->getManager();
            try{
                $manager->remove($contacto);
                $manager->flush();
            }catch(Exception $e)
            {
                return new Response("Error " . $e->getMessage());
            }
        }
        
        return new Response("Contacto borrado");
    }

    #[Route('/contacto/buscar/{texto}', name: 'buscar_contacto')]
    public function buscar(ManagerRegistry $doctrine, string $texto): Response    
    {
        $repositorio = $doctrine->getRepository(Contacto::class);

        $resultado = $repositorio->findByNombre($texto);

        // $resultado = array_filter($this->contactos, 
        //     function ($contacto) use ($texto){
        //         return strpos($contacto["nombre"], $texto) !== false;
        //     }
        // );
        return $this->render('contactos.html.twig', [
            'contactos' => $resultado, 'texto' => $texto
        ]);
    }
}
