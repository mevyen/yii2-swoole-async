<?php
namespace console\components;

use Yii;
use yii\base\Model;

/**
 * Description of BaseModel
 * 模型基类
 * @author wangjiacheng
 */
class BaseModel extends Model {
    
    /**
     * 默认映射方法
     * @var type 
     */
    public $mappingMethod = 'mapping';

    /**
     * 内置验证器验证类型配置
     */
    const FILTER_REQUIRED = 'required';
    const FILTER_INTEGER = 'integer';
    const FILTER_BOOLEAN = 'boolean';
    const FILTER_DOUBLE = 'double';
    const FILTER_NUMBER = 'number';
    const FILTER_STRING = 'string';
    const FILTER_SAFE = 'safe';
    const FILTER_DATE = 'date';
    const FILTER_EACH = 'each';
    const FILTER_FILTER = 'filter';
    const FILTER_IMAGE = 'image';
    const FILTER_MATCH = 'match';
    const FILTER_EMAIL = 'email';
    const FILTER_URL = 'url';
    const FILTER_TRIM = 'trim';
    const FILTER_UNIQUE = 'unique';
    const FILTER_COMPARE = 'compare';
    const FILTER_LENGTH = 'length';
    const FILTER_IN = 'in';
    const FILTER_NUMERICAL = 'numerical';
    const FILTER_CAPTCHA = 'captcha';
    const FILTER_TYPE = 'type';
    const FILTER_FILE = 'file';
    const FILTER_DEFAULT = 'default';
    const FILTER_EXIST = 'exist';
    
    public function init() {
        
    }
    
    /**
     * 赋值字段映射
     */
    public function mapping() {
        return [];
    }
       
    /**
     * 默认字段格式化方式
     */
    public function set__default($field) {
        return $field;
    }
    
    /**
     * 映射事件处理
     * @param type $data    二维关联数组
     * @return type
     */
    public function mapHandle($data = []) {
        if (empty($data)) return [];
        
        $result = [];
        $mappingMethod = $this->mappingMethod;
        foreach($data as $i => $row) {

            foreach($this->$mappingMethod() as $k => $v) {

                $method = 'set'. self::normalizeRoute($k);
                $result[$i][$k] = method_exists($this, $method) ? $this->$method($v, $row) : (!empty($v) ? $this->set__default($row[$v]) : '');    
            }
        }
        return $result;
    }
    
    /**
     * 返回驼峰字符串
     * @param type $str
     * @return type
     */
    static public function normalizeRoute($str) {
        if (strpos($str, '_') === false) {
            if (strpos($str, '-') !== false) {
                $string = explode('-', $str);
                $normalize = '';
                foreach($string as $r) {
                    $normalize .= ucfirst($r);
                }
                return $normalize;
            }
            return ucfirst($str);
        } else {
            $string = explode('_', $str);
            $normalize = '';
            foreach($string as $r) {
                $normalize .= ucfirst($r);
            }
            return $normalize;
        }
    }
}
