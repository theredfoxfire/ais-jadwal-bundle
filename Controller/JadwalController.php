<?php

namespace Ais\DosenBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Ais\DosenBundle\Exception\InvalidFormException;
use Ais\DosenBundle\Form\DosenType;
use Ais\DosenBundle\Model\DosenInterface;


class DosenController extends FOSRestController
{
    /**
     * List all dosens.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing dosens.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many dosens to return.")
     *
     * @Annotations\View(
     *  templateVar="dosens"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getDosensAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('ais_dosen.dosen.handler')->all($limit, $offset);
    }

    /**
     * Get single Dosen.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Dosen for a given id",
     *   output = "Ais\DosenBundle\Entity\Dosen",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the dosen is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="dosen")
     *
     * @param int     $id      the dosen id
     *
     * @return array
     *
     * @throws NotFoundHttpException when dosen not exist
     */
    public function getDosenAction($id)
    {
        $dosen = $this->getOr404($id);

        return $dosen;
    }

    /**
     * Presents the form to use to create a new dosen.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newDosenAction()
    {
        return $this->createForm(new DosenType());
    }
    
    /**
     * Presents the form to use to edit dosen.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisDosenBundle:Dosen:editDosen.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the dosen id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when dosen not exist
     */
    public function editDosenAction($id)
    {
		$dosen = $this->getDosenAction($id);
		
        return array('form' => $this->createForm(new DosenType(), $dosen), 'dosen' => $dosen);
    }

    /**
     * Create a Dosen from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new dosen from the submitted data.",
     *   input = "Ais\DosenBundle\Form\DosenType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisDosenBundle:Dosen:newDosen.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postDosenAction(Request $request)
    {
        try {
            $newDosen = $this->container->get('ais_dosen.dosen.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newDosen->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_dosen', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing dosen from the submitted data or create a new dosen at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\DosenBundle\Form\DosenType",
     *   statusCodes = {
     *     201 = "Returned when the Dosen is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisDosenBundle:Dosen:editDosen.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the dosen id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when dosen not exist
     */
    public function putDosenAction(Request $request, $id)
    {
        try {
            if (!($dosen = $this->container->get('ais_dosen.dosen.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $dosen = $this->container->get('ais_dosen.dosen.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $dosen = $this->container->get('ais_dosen.dosen.handler')->put(
                    $dosen,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $dosen->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_dosen', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing dosen from the submitted data or create a new dosen at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\DosenBundle\Form\DosenType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisDosenBundle:Dosen:editDosen.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the dosen id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when dosen not exist
     */
    public function patchDosenAction(Request $request, $id)
    {
        try {
            $dosen = $this->container->get('ais_dosen.dosen.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $dosen->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_dosen', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch a Dosen or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return DosenInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($dosen = $this->container->get('ais_dosen.dosen.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $dosen;
    }
    
    public function postUpdateDosenAction(Request $request, $id)
    {
		try {
            $dosen = $this->container->get('ais_dosen.dosen.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $dosen->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_dosen', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
	}
}
