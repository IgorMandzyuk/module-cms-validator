<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Im\CMSValidator\Plugin\Backend\Magento\Cms\Controller\Adminhtml\Page;

use Im\CMSValidator\Model\Validators\CSS;
use Im\CMSValidator\Model\Validators\HTML;
use Im\CMSValidator\Model\Validators\JS;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;

class Edit
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var CSS
     */
    private $css;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var JS
     */
    private $js;

    /**
     * @var HTML
     */
    private $html;

    /**
     * @param ManagerInterface $messageManager
     * @param Registry $registry
     * @param HTML $html
     * @param CSS $css
     * @param JS $js
     */
    public function __construct(
        ManagerInterface $messageManager,
        Registry $registry,
        HTML $html,
        CSS $css,
        JS $js
    )
    {
        $this->messageManager = $messageManager;
        $this->registry = $registry;
        $this->html = $html;
        $this->css = $css;
        $this->js = $js;
    }

    /**
     * @param \Magento\Cms\Controller\Adminhtml\Page\Edit $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(
        \Magento\Cms\Controller\Adminhtml\Page\Edit $subject,
                                                     $result
    ) {
        $errors = $this->html->execute($this->registry->registry('cms_page')->getContent());
        var_dump(        $errors = $this->css->execute($this->registry->registry('cms_page')->getContent()));
        if (count($errors)){
            foreach ($errors as $error){
                $this->messageManager->addErrorMessage(__($error));
            }
        }else {
            $this->messageManager->addSuccessMessage(__('The HTML code in block is valid!'));
        }

        return $result;
    }
}
