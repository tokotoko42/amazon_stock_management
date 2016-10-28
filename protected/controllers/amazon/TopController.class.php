<?php
/*
 * Top
 *
 * @package
 * @subpackage
 * @author Kamijo Tetsuya
 * @version $Revision$
 * $Id$
 */

class TopController extends PCController
{
    /**
     * Top Page
     */
    public function actionIndex()
    {
        $this->setPageTitle('Amazon Stock Management');
    }
}
