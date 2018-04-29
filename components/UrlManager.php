<?php

namespace app\components;

use yii;

class UrlManager extends yii\web\UrlManager {

    public function parseRequest($request) {
        if ($this->enablePrettyUrl) {
            /* @var $rule UrlRule */
            foreach ($this->rules as $rule) {
                if (($result = $rule->parseRequest($this, $request)) !== false) {
                    return $result;
                }
            }

            if ($this->enableStrictParsing) {
                return false;
            }

            Yii::trace('No matching URL rules. Using default URL parsing logic.', __METHOD__);
            $params = [];
            if (!empty($request->get('q'))) {
                $str = \Yii::$app->general->base64url_decode(($request->get('q')));
                $data = @unserialize($str);
                if ($str === 'b:0;' || $data !== false) {
                    $params = unserialize($str);
                } else {
                    parse_str(urldecode($str), $params);
                }
            }
            $suffix = (string) $this->suffix;
            $pathInfo = $request->getPathInfo();
            $normalized = false;
            if ($this->normalizer !== false) {
                $pathInfo = $this->normalizer->normalizePathInfo($pathInfo, $suffix, $normalized);
            }
            if ($suffix !== '' && $pathInfo !== '') {
                $n = strlen($this->suffix);
                if (substr_compare($pathInfo, $this->suffix, -$n, $n) === 0) {
                    $pathInfo = substr($pathInfo, 0, -$n);
                    if ($pathInfo === '') {
                        // suffix alone is not allowed
                        return false;
                    }
                } else {
                    // suffix doesn't match
                    return false;
                }
            }

            if ($normalized) {
                // pathInfo was changed by normalizer - we need also normalize route
                return $this->normalizer->normalizeRoute([$pathInfo, []]);
            } else {
                $pathInfo = Yii::$app->general->base64url_decode($pathInfo);
                return [$pathInfo, $params];
            }
        } else {
            Yii::trace('Pretty URL not enabled. Using default URL parsing logic.', __METHOD__);
            $route = $request->getQueryParam($this->routeParam, '');
            if (is_array($route)) {
                $route = '';
            }

            return [(string) $route, $params];
        }
    }

}
