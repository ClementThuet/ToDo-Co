<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Doctrine\ORM\Query\AST\LikeExpression;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        //dd($request->getRequestUri());
        if(strpos($request->getRequestUri(),"edit") == true )
        {
            $request->getSession()->getFlashBag()->add('error', 'Vous n\'avez pas la permission de modifier cela.');
            return new RedirectResponse('/');
        }
        if(strpos($request->getRequestUri(),"delete") == true )
        {
            $request->getSession()->getFlashBag()->add('error', 'Vous n\'avez pas la permission de supprimer cette tâche.');
            return new RedirectResponse('/');
        }
        
        if($request->getRequestUri() == "/users" )
        {
            $request->getSession()->getFlashBag()->add('error', 'Vous n\'avez pas la permission d\'accéder à la liste des utilisateurs.');
            return new RedirectResponse('/');
        }
        return new RedirectResponse('/');
    }
}