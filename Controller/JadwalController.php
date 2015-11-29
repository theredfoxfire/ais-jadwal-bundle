<?php

namespace Ais\JadwalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Ais\JadwalBundle\Exception\InvalidFormException;
use Ais\JadwalBundle\Form\JadwalType;
use Ais\JadwalBundle\Model\JadwalInterface;


class JadwalController extends FOSRestController
{
    /**
     * List all jadwals.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing jadwals.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many jadwals to return.")
     *
     * @Annotations\View(
     *  templateVar="jadwals"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getJadwalsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('ais_jadwal.jadwal.handler')->all($limit, $offset);
    }

    /**
     * Get single Jadwal.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Jadwal for a given id",
     *   output = "Ais\JadwalBundle\Entity\Jadwal",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the jadwal is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="jadwal")
     *
     * @param int     $id      the jadwal id
     *
     * @return array
     *
     * @throws NotFoundHttpException when jadwal not exist
     */
    public function getJadwalAction($id)
    {
        $jadwal = $this->getOr404($id);

        return $jadwal;
    }

    /**
     * Presents the form to use to create a new jadwal.
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
    public function newJadwalAction()
    {
        return $this->createForm(new JadwalType());
    }
    
    /**
     * Presents the form to use to edit jadwal.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisJadwalBundle:Jadwal:editJadwal.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the jadwal id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when jadwal not exist
     */
    public function editJadwalAction($id)
    {
		$jadwal = $this->getJadwalAction($id);
		
        return array('form' => $this->createForm(new JadwalType(), $jadwal), 'jadwal' => $jadwal);
    }

    /**
     * Create a Jadwal from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new jadwal from the submitted data.",
     *   input = "Ais\JadwalBundle\Form\JadwalType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisJadwalBundle:Jadwal:newJadwal.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postJadwalAction(Request $request)
    {
        try {
            $newJadwal = $this->container->get('ais_jadwal.jadwal.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newJadwal->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_jadwal', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing jadwal from the submitted data or create a new jadwal at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\JadwalBundle\Form\JadwalType",
     *   statusCodes = {
     *     201 = "Returned when the Jadwal is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisJadwalBundle:Jadwal:editJadwal.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the jadwal id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when jadwal not exist
     */
    public function putJadwalAction(Request $request, $id)
    {
        try {
            if (!($jadwal = $this->container->get('ais_jadwal.jadwal.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $jadwal = $this->container->get('ais_jadwal.jadwal.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $jadwal = $this->container->get('ais_jadwal.jadwal.handler')->put(
                    $jadwal,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $jadwal->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_jadwal', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing jadwal from the submitted data or create a new jadwal at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\JadwalBundle\Form\JadwalType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisJadwalBundle:Jadwal:editJadwal.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the jadwal id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when jadwal not exist
     */
    public function patchJadwalAction(Request $request, $id)
    {
        try {
            $jadwal = $this->container->get('ais_jadwal.jadwal.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $jadwal->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_jadwal', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch a Jadwal or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return JadwalInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($jadwal = $this->container->get('ais_jadwal.jadwal.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $jadwal;
    }
    
    public function postUpdateJadwalAction(Request $request, $id)
    {
		try {
            $jadwal = $this->container->get('ais_jadwal.jadwal.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $jadwal->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_jadwal', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
	}
}
