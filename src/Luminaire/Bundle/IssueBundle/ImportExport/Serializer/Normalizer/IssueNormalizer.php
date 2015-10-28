<?php

namespace Luminaire\Bundle\IssueBundle\ImportExport\Serializer\Normalizer;

use Luminaire\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\ConfigurableEntityNormalizer;

/**
 * Class IssueNormalizer
 */
class IssueNormalizer extends ConfigurableEntityNormalizer
{
    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Issue;
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return is_array($data) && $type == 'Luminaire\Bundle\IssueBundle\Entity\Issue';
    }
}
