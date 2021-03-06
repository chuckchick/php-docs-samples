<?php
/**
 * Copyright 2020 Google LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Samples\Functions\HttpContentType\Test;

use Google\Cloud\TestUtils\CloudFunctionDeploymentTrait;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/TestCasesTrait.php';

/**
 * Class DeployTest.
 *
 * This test is not run by the CI system.
 *
 * To skip deployment of a new function, run with "GOOGLE_SKIP_DEPLOYMENT=true".
 * To skip deletion of the tested function, run with "GOOGLE_KEEP_DEPLOYMENT=true".
 */
class DeployTest extends TestCase
{
    use CloudFunctionDeploymentTrait;
    use TestCasesTrait;

    private static $entryPoint = 'helloContent';

    public function testFunction(): void
    {
        foreach (self::cases() as $test) {
            $resp = $this->client->post('', [
                'headers' => ['content-type' => $test['content-type']],
                'body' => $test['body'],
                // Uncomment and CURLOPT_VERBOSE debug content will be sent to stdout.
                // 'debug' => true,
            ]);

            $actual = trim((string) $resp->getBody());

            $this->assertEquals($test['code'], $resp->getStatusCode(), $test['content-type'] . ':');
            // Failures often lead to a large HTML page in the response body.
            $this->assertStringContainsString($test['expected'], $actual, $test['content-type'] . ':');
        }
    }
}
