<?php

namespace Im\CMSValidator\Model\Validators;

class JS
{
    protected $resultJsonFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context            $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $jsCode = $this->getRequest()->getParam('js_code');

        // Perform JavaScript code validation
        $validationResults = $this->validateJsCode($jsCode);

        // Return the validation results as JSON response
        $result = $this->resultJsonFactory->create();
        return $result->setData($validationResults);
    }

    protected function validateJsCode($jsCode)
    {
        // Load jslint.js file
        $jslintPath = $this->_objectManager->get('Magento\Framework\View\Asset\Repository')
            ->createAsset('Vendor_Module::js/jslint.js')
            ->getSourceFile();

        // Run JSHint command and capture the output
        exec('jshint ' . $jslintPath . ' -- ' . escapeshellarg($jsCode), $output, $returnVar);

        // Process and format the validation results
        $validationResults = [];
        if ($returnVar === 0) {
            foreach ($output as $error) {
                $parts = explode(':', $error, 4);
                $line = (int)$parts[1];
                $column = (int)$parts[2];
                $message = trim($parts[3]);
                $validationResults[] = [
                    'line' => $line,
                    'column' => $column,
                    'message' => $message
                ];
            }
        }

        return $validationResults;
    }
}