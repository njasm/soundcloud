<?php

namespace Njasm\Soundcloud\Resource;

class Track extends AbstractResource
{

    public function download($download = true)
    {
        $this->isNewLogicalException(true, "Resource is new.");

        $trackID = $this->get('id');
        return $this->sc->download($trackID, $download);
    }

    public function save()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}