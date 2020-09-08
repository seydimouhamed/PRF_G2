<?php

namespace App\Tests;
    /**
     * 
     */
    trait TraitToken
    {
        public function getToken()
        {
            $token="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1OTkwNjA1NjcsImV4cCI6MTU5OTA2NDE2Nywicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6ImFkbWluMSJ9.OuQzsRZOtic-qtmAbmxjNcWtQ2ViNSWyPFKRS5AUyW27QmqO9ZU9Hd04fLR3mdcHkZmORuLrfjMWf_gvfH3PLuchc83_cmFinF9UfiOgjt6PlCIH5HJQnUzuqz2EX7knlN5IcPp85YRUsE1UkOOEhfqu6A24S42BThnGzsS1s9_aGYyTlo6KmUjkNyw6_VPx5RbfJ6kK7IECqVCiV-J7xIWBvPl7SQRvKVBn4EhBhe6q7zK3FC1mOMvrEC-1ywppmJHBVoBpcqNTR_X4hjupuo8cMsvVlO8nWStZyjzd0WH1DiWthnI_KMh2_giMIbjkCTwa84mq_xhLaO2RQGZwZ0H7tZRnA_MEccWR33r5Letk-7TtGuPyJaz4qrdjWRN6JT7GHgvPca1B_9YpZphs1xV2JFdCe2N7E0UbjGzImuTDP_h-pSfYJ8qlKgdc9jY0lpvlR7TE82zrNsYx_4A7IV9d7_-AQd4K1Ty7mpJIiWsk6LW3lDuG-U3qujLju3nsUySLRaLrDaVPmqVzoT5e_6JyV0YKcXKB-DsosV3TsmEyhh64gYgNVatqxkQlLUDwY8dBCOzdCbUpmvKZD0axncnjN5vnYaCgHrimH-fDEzUCafZZEO3IPvxt77E11eyZH-hTMNu2RvOHnaS2AqI51EXZdQQ-yB6KMXGkaG9yDJ0";

            return "Bearer $token";
        }
    }
    