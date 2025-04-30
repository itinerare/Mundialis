<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PageViewFieldTest extends TestCase {
    use RefreshDatabase, WithFaker;

    /**
     * Test page access with an infobox field.
     *
     * @param string $fieldType
     * @param bool   $withInput
     */
    #[DataProvider('getPageWithFieldProvider')]
    public function testGetPageWithInfoboxField($fieldType, $withInput) {
        // Set up some data for the field
        $fieldData = [
            'key'   => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        if ($fieldType == 'choice' || $fieldType == 'multiple') {
            for ($i = 0; $i <= 1; $i++) {
                $fieldData['options'][$i] = $this->faker->unique()->domainWord();
            }
        }

        // Create a category with the relevant data directly
        $category = SubjectCategory::factory()->infoboxField(
            $fieldData['key'],
            $fieldData['label'],
            $fieldType,
            null,
            $fieldType == 'choice' || $fieldType == 'multiple' ? json_encode($fieldData['options']) : null,
        )->create();

        if ($withInput) {
            switch ($fieldType) {
                case 'text': case 'textarea':
                    $data[$fieldData['key']] = $this->faker->unique()->domainWord();
                    break;
                case 'number':
                    $data[$fieldData['key']] = mt_rand(1, 50);
                    break;
                case 'checkbox':
                    $data[$fieldData['key']] = 1;
                    break;
                case 'choice':
                    $data[$fieldData['key']] = 1;
                    break;
                case 'multiple':
                    $data[$fieldData['key']] = '["1","1"]';
                    break;
            }
            $inputString = is_numeric($data[$fieldData['key']]) || $fieldType == 'multiple' ? $data[$fieldData['key']] : '"'.$data[$fieldData['key']].'"';
        }

        // Directly create the page and associated version with the requisite data
        $page = Page::factory()->category($category->id)->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create([
                'data' => '{"data":{"description":null,"'.$fieldData['key'].'":'.($withInput ? $inputString : 'null').',"parsed":{"description":null,"'.$fieldData['key'].'":'.($withInput ? $inputString : 'null').'}},"title":"'.$page->title.'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
            ]);

        $response = $this->actingAs($this->user)
            ->get('/pages/'.$page->id.'.'.$page->slug)
            ->assertStatus(200);

        if ($withInput) {
            $response->assertSeeText($fieldData['label']);

            switch ($fieldType) {
                case 'checkbox':
                    $response->assertSee('fas fa-check text-success');
                    break;
                case 'choice':
                    $response->assertSee($fieldData['options'][$data[$fieldData['key']]]);
                    break;
                case 'multiple':
                    foreach ($fieldData['options'] as $option) {
                        $response->assertSee($option);
                    }
                    break;
                default:
                    $response->assertSee($data[$fieldData['key']]);
                    break;
            }
        }
    }

    /**
     * Test page access with a main body field.
     *
     * @param string $fieldType
     * @param bool   $withInput
     */
    #[DataProvider('getPageWithFieldProvider')]
    #[DataProvider('getPageWithTemplateFieldProvider')]
    public function testGetPageWithTemplateField($fieldType, $withInput) {
        // Set up some data for the field
        $fieldData = [
            'key'   => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        if ($fieldType == 'choice' || $fieldType == 'multiple') {
            for ($i = 0; $i <= 1; $i++) {
                $fieldData['options'][$i] = $this->faker->unique()->domainWord();
            }
        }

        // Create a category with the relevant data directly
        $category = SubjectCategory::factory()->bodyField(
            $fieldData['key'],
            $fieldData['label'],
            $fieldType,
            null,
            $fieldType == 'choice' || $fieldType == 'multiple' ? json_encode($fieldData['options']) : null,
        )->create();

        if ($withInput) {
            switch ($fieldType) {
                case 'text': case 'textarea':
                    $data[$fieldData['key']] = $this->faker->unique()->domainWord();
                    break;
                case 'number':
                    $data[$fieldData['key']] = mt_rand(1, 50);
                    break;
                case 'checkbox':
                    $data[$fieldData['key']] = 1;
                    break;
                case 'choice':
                    $data[$fieldData['key']] = 1;
                    break;
                case 'multiple':
                    $data[$fieldData['key']] = '["1","1"]';
                    break;
            }
            $inputString = is_numeric($data[$fieldData['key']]) || $fieldType == 'multiple' ? $data[$fieldData['key']] : '"'.$data[$fieldData['key']].'"';
        }

        // Directly create the page and associated version with the requisite data
        $page = Page::factory()->category($category->id)->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create([
                'data' => '{"data":{"description":null,"'.$fieldData['key'].'":'.($withInput ? $inputString : 'null').',"parsed":{"description":null,"'.$fieldData['key'].'":'.($withInput ? $inputString : 'null').'}},"title":"'.$page->title.'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
            ]);

        $response = $this->actingAs($this->user)
            ->get('/pages/'.$page->id.'.'.$page->slug)
            ->assertStatus(200);

        if ($withInput) {
            switch ($fieldType) {
                case 'checkbox':
                    $response->assertSee('fas fa-check text-success');
                    break;
                case 'choice':
                    $response->assertSee($fieldData['options'][$data[$fieldData['key']]]);
                    break;
                case 'multiple':
                    foreach ($fieldData['options'] as $option) {
                        $response->assertSee($option);
                    }
                    break;
                default:
                    $response->assertSee($data[$fieldData['key']]);
                    break;
            }
        }
    }

    public static function getPageWithFieldProvider() {
        return [
            'text'                       => ['text', 0],
            'text with input'            => ['text', 1],
            'number'                     => ['number', 0],
            'number with input'          => ['number', 1],
            'checkbox'                   => ['checkbox', 0],
            'checkbox with input'        => ['checkbox', 1],
            'choose one'                 => ['choice', 0],
            'choose one with input'      => ['choice', 1],
            'choose multiple'            => ['multiple', 0],
            'choose multiple with input' => ['multiple', 1],
        ];
    }

    public static function getPageWithTemplateFieldProvider() {
        return [
            'textbox'            => ['textarea', 0],
            'textbox with input' => ['textarea', 1],
        ];
    }
}
