<?php
namespace Infobeans\PushNotification\Ui\Component\Template;
 
use Magento\Framework\Data\OptionSourceInterface;
use Infobeans\PushNotification\Model\ResourceModel\Templates\CollectionFactory as TemplateCollectionFactory;
use Magento\Framework\App\RequestInterface;
 
/**
 * Options tree for "Template" field
 */
class Options implements OptionSourceInterface
{
    /**
     * @var \Infobeans\PushNotification\Model\ResourceModel\Templates\CollectionFactory
     */
    protected $templateCollectionFactory;
 
    /**
     * @var RequestInterface
     */
    protected $request;
 
    /**
     * @var array
     */
    protected $templateTree;
 
    /**
     * @param TemplateCollectionFactory $templateCollectionFactory
     * @param RequestInterface $request
     */
    public function __construct(
        TemplateCollectionFactory $templateCollectionFactory,
        RequestInterface $request
    ) {
        $this->templateCollectionFactory = $templateCollectionFactory;
        $this->request = $request;
    }
 
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getTemplateTree();
    }
 
    /**
     * Retrieve categories tree
     *
     * @return array
     */
    protected function getTemplateTree()
    {
        $templateById = [];
        if ($this->templateTree === null) {
            $collection = $this->templateCollectionFactory->create();
 
            foreach ($collection as $template) {
                $templateId = $template->getId();
                if (!isset($templateById[$templateId])) {
                    $templateById[$templateId] = [
                        'value' => $templateId
                    ];
                }
                $templateById[$templateId]['label'] = $template->getTitle();
            }
            $this->templateTree = $templateById;
        }
        return $this->templateTree;
    }
}