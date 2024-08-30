<?php
/**
 * @category  Scandiweb
 * @package   Scandiweb_Grid
 * @author    Kirolos Nashed <kirolos.nashed@scandiweb.com>
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\Grid\Controller\Adminhtml\AdminUser;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Ui\Component\MassAction\Filter;
use Magento\User\Model\ResourceModel\User\CollectionFactory;

/**
 * Class MassDelete
 */
class MassDelete extends Action
{
    const ADMIN_RESOURCE = 'Magento_User::all';

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        protected Context $context,
        protected Filter $filter,
        protected CollectionFactory $collectionFactory,
    ) {
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     * @throws NotFoundException
     * @throws LocalizedException
     */
    public function execute(): ResultInterface
    {
        if (!$this->getRequest()->isPost()) {
            throw new NotFoundException(__('Page not found'));
        }

        $selected = $this->getRequest()->getParam('selected');
        $usersDeleted = 0;

        if (!empty($selected)) {
            try {
                $collection = $this->collectionFactory->create();
                $collection->addFieldToFilter('user_id', ['in' => $selected]);

                foreach ($collection as $adminUser) {
                    $adminUser->delete();
                    $usersDeleted++;
                }

                if ($usersDeleted) {
                    $this->messageManager->addSuccessMessage(
                        __('A total of %1 record(s) have been deleted.', $usersDeleted)
                    );
                }

            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        } else {
            $this->messageManager->addWarningMessage(__('Please select admin users to delete.'));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('scandiweb_grid/index/index');
    }
}
