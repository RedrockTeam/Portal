<?php

	namespace Home\Controller;
	use Think\Controller;
	class WeatherController extends Controller {

		/**
		 * [index 天气接口]
		 * @return [Array] [天气信息]
		 */
		public function index(){
			 return $this->_dealWeatherInfo(C('CITY_ID'));
		}

		/**
		 * [处理获取到的天气信息]
		 * @return [type] [description]
		 */
		private function _dealWeatherInfo($cityID){
			$Weather = M('weather', '', 'DB_CONFIG1'); // 125
			$lastWeather = $Weather->find($Weather->max('wea_id'));
			if(time() - $lastWeather['wea_save_date'] > 3600){
				$url = 'http://www.weather.com.cn/data/cityinfo/'.$cityID.'.html';
				$weaterInfo = json_decode($this->_getWeatherInfo($url));
				if($weaterInfo){
					foreach($weaterInfo as $value){
						$data['wea_city'] = $value->city;
						$data['wea_min_temp'] = $value->temp1;
						$data['wea_max_temp'] = $value->temp2;
						$data['wea_info'] = $value->weather;
						$data['wea_save_date'] = time();
					}
					$Weather->add($data);
					return $data;
				}else {
					return $lastWeather;
				}
			}else {
				return $lastWeather;
			}
		}

		/**
		 * [Curl请求]
		 * @param  [string] $url [GET请求地址]
		 * @return [json]        [返回天气信息]
		 */
		private function _getWeatherInfo($url) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$weaterInfo = curl_exec($curl); // 执行cURL抓取页面内容
			curl_close($curl);
			return $weaterInfo;
		}


	}


?>