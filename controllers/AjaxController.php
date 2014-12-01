<?php
/**
 * The Simple Pages Ajax controller class.
 *
 * @package SimplePages
 */
class SimplePages_AjaxController extends Omeka_Controller_AbstractActionController
{
    /**
     * Controller-wide initialization. Sets the underlying model to use.
     */
    public function init()
    {
        // Don't render the view script.
        $this->_helper->viewRenderer->setNoRender(true);

        $this->_helper->db->setDefaultModelName('SimplePagesPage');
    }

    /**
     * Handle AJAX requests to update a simple page.
     */
    public function updateAction()
    {
        if (!$this->_checkAjax('update')) {
            return;
        }

        // Handle action.
        try {
            $status = $this->_getParam('status');
            if (!in_array($status, array('public', 'private'))) {
                $this->getResponse()->setHttpResponseCode(400);
                return;
            }

            $id = (integer) $this->_getParam('id');
            $simplePage = $this->_helper->db->find($id);
            if (!$simplePage) {
                $this->getResponse()->setHttpResponseCode(400);
                return;
            }
            $simplePage->is_published = ($status == 'public');
            $simplePage->save();
        } catch (Exception $e) {
            $this->getResponse()->setHttpResponseCode(500);
        }
    }

    /**
     * Handle AJAX requests to delete a simple page.
     */
    public function deleteAction()
    {
        if (!$this->_checkAjax('delete')) {
            return;
        }

        // Handle action.
        try {
            $id = (integer) $this->_getParam('id');
            $simplePage = $this->_helper->db->find($id);
            if (!$simplePage) {
                $this->getResponse()->setHttpResponseCode(400);
                return;
            }
            $simplePage->delete();
        } catch (Exception $e) {
            $this->getResponse()->setHttpResponseCode(500);
        }
    }

    /**
     * Handle AJAX requests to change a list of simple pages.
     */
    public function changeAction()
    {
        if (!$this->_checkAjax('change')) {
            return;
        }

        // Handle action.
        try {
            $remove = $this->_getParam('remove');
            $remove = $remove ?: array();
            if (!is_array($remove)) {
                $this->getResponse()->setHttpResponseCode(400);
                return;
            }

            $order = $this->_getParam('order');
            if (!is_array($order) || empty($order)) {
                $this->getResponse()->setHttpResponseCode(400);
                return;
            }

            // Secure and normalize order.
            $newOrder = array();
            // Remove root.
            unset($order[0]);
            foreach ($order as $input) {
                $newOrder[(integer) $input['id']] = (integer) $input['parent_id'];
            }

            // Delete pages to remove and update order array.
            foreach ($remove as $id) {
                $id = (integer) $id;
                $simplePage = $this->_helper->db->find($id);
                if (!$simplePage) {
                    $this->getResponse()->setHttpResponseCode(400);
                    return;
                }
                // Remove deleted pages from new order and attach children to
                // new parent.
                $newParentId = $newOrder[$id];
                unset($newOrder[$id]);
                foreach ($newOrder as &$parentId) {
                    if ($parentId == $id) {
                        $parentId = $newParentId;
                    }
                }
                $simplePage->delete();
            }

            // Reorder pages if needed.
            simple_pages_update_order($newOrder);
        } catch (Exception $e) {
            $this->getResponse()->setHttpResponseCode(500);
        }
    }

    /**
     * Check AJAX requests.
     *
     * 400 Bad Request
     * 403 Forbidden
     * 500 Internal Server Error
     *
     * @param string $action
     */
    protected function _checkAjax($action)
    {
        // Only allow AJAX requests.
        $request = $this->getRequest();
        if (!$request->isXmlHttpRequest()) {
            $this->getResponse()->setHttpResponseCode(403);
            return false;
        }

        // Allow only valid calls.
        if ($request->getControllerName() != 'ajax'
                || $request->getActionName() != $action
            ) {
            $this->getResponse()->setHttpResponseCode(400);
            return false;
        }

        // Allow only allowed users.
        if (!is_allowed('SimplePages_Page', $action)) {
            $this->getResponse()->setHttpResponseCode(403);
            return false;
        }

        return true;
    }
}
