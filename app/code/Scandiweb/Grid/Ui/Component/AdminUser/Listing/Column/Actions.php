<?php
/**
 * @category  Scandiweb
 * @package   Scandiweb_Grid
 * @author    Kirolos Nashed <kirolos.nashed@scandiweb.com>
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\Grid\Ui\Component\AdminUser\Listing\Column;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class MassDelete
 */
class Actions extends Column
{
    const URL_HELLO = 'scandiweb_grid/actions/index';

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $backendUrl
     * @param string $viewUrl
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        protected UrlInterface $backendUrl,
        protected string $viewUrl = '',
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');

                if (isset($item['user_id'])) {
                    $item[$name] = [
                        'edit' => [
                            'href' => $this->backendUrl->getUrl($this->viewUrl, ['user_id' => $item['user_id']]),
                            'label' => __('Edit user')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
