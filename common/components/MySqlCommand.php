<?php

namespace common\components;

use Yii;

class MySqlCommand extends \yii\db\Command {
//    const EVENT_DISCONNECT = 'disconnect';

    private $retry = false;

    public function execute() {
        try {
            return parent::execute();
        } catch (\yii\db\Exception $e) {
            if ($this->shouldRetry($e)) {
                return parent::execute();
            } else {
                throw $e;
            }
        }
    }

    protected function queryInternal($method, $fetchMode = null) {
        try {
            return parent::queryInternal($method, $fetchMode);
        } catch (\yii\db\Exception $e) {
            if ($this->shouldRetry($e)) {
                return parent::queryInternal($method, $fetchMode);
            }
            throw $e;
        }
    }

    /**
     * 判断该数据库异常是否需要重试.一般情况下链接断开的错误才需要重试
     * 2006: MySQL server has gone away
     * 2013: Lost connection to MySQL server during query
     * 但是实际使用中发现，由于Yii2对数据库异常进行了处理并封装成\yii\db\Exception异常
     * 因此2006错误的错误码并不能在errorInfo中获取到，因此需要判断errorMsg内容
     * @param \yii\db\Exception $ex
     * @return bool
     * @throws \yii\db\Exception
     */
    private function shouldRetry(\yii\db\Exception $ex) {
        Yii::info($ex);

        $errorMsg = $ex->getMessage();
        $offset = strpos($errorMsg, 'MySQL server has gone away');
        $offset2 = stripos($errorMsg, 'Error while sending QUERY packet');
        if ($offset === false && $offset2 === false && !in_array($ex->errorInfo[1], [2006, 2013])) {
            return false;
        }
//        $this->trigger(static::EVENT_DISCONNECT);
        Yii::warning("mysql reconnect");

        $this->retry = true;
        $this->pdoStatement = null;
        $this->db->close();
        $this->db->open();
        return true;
    }

    /**
     * 重写bindPendingParams方法，当当前是数据库重连之后重试的时候
     * 调用bindValues方法重写绑定一次参数.
     */
    protected function bindPendingParams() {
        if ($this->retry) {
            $this->retry = false;
            $this->bindValues($this->params);
        }
        parent::bindPendingParams();
    }
}

?>