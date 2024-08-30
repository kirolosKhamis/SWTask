<?php
/**
 * @category  Scandiweb
 * @package   Scandiweb_Grid
 * @author    Kirolos Nashed <kirolos.nashed@scandiweb.com>
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\Grid\Ui\DataProvider\AdminUser\Listing;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Zend_Db_Expr;

/**
 * Class Collection
 */
class Collection extends SearchResult
{
    /**
     * @return void
     */
    protected function _initSelect()
    {
        $this->setMainTable('admin_user');
        $this->_setIdFieldName('user_id');
        parent::_initSelect();
        $this->joinAdminAuthorizationRole();
    }

    /**
     * @return $this
     */
    public function joinAdminAuthorizationRole()
    {
        $this->getSelect()
            ->joinLeft(
                ['aur' => $this->getTable('authorization_role')],
                'aur.user_id = main_table.user_id',
                ['role_name' => $this->getRoleNameExpression()]
            );

        return $this;
    }

    /**
     * @return Zend_Db_Expr
     */
    public function getRoleNameExpression()
    {
        // Get role name from authorization_role table
        $connection = $this->getConnection();

        return $connection->getIfNullSql(
            $connection->quoteIdentifier('aur.role_name'),
            0
        );
    }
}
