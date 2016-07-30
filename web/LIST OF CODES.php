routing:
            pattern: /login{trailingSlash}
    defaults: { _controller: CoreBundle:Default:login,trailingSlash : "/" }
    requirements: { trailingSlash : "[/]{0,1}" }




------------


security:
 login:
            pattern:  ^/login$
            security: false

			if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
				$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
			} else {
				$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
				$session->remove(SecurityContext::AUTHENTICATION_ERROR);
			}

			return $this->render('CoreBundle:Core:login.html.twig', array(
				// last username entered by the user
				'last_username' => $session->get(SecurityContext::LAST_USERNAME),
				'error' => $error,
			));
