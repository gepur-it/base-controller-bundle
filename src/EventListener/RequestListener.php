<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 18.01.19
 */

namespace GepurIt\BaseControllerBundle\EventListener;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

/**
 * @todo move it to GepurIt base_controller-bundle
 * Class RequestListener
 * @package App\EventListener
 */
class RequestListener
{
    /**
     * Core request handler.
     *
     * @param GetResponseEvent $event
     *
     * @throws BadRequestHttpException
     * @throws UnsupportedMediaTypeHttpException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $contentType = $request->headers->get('Content-Type');

        $format = null === $contentType
            ? $request->getRequestFormat()
            : $request->getFormat($contentType);

        if ($format !== 'json') {
            return;
        }

        $content = $request->getContent();
        $request->setFormat($format, $contentType);

        $data = @json_decode($content, true);
        if (null === $data) {
            $data = [];
        }
        $request->request = new ParameterBag($data);
    }

}
