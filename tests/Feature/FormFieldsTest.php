<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Spdotdev\SplotnikovDev\Tests\TestCase;

/** The contact form helps phones and password managers: keyboard type, enter key label, autocomplete on every field. */
class FormFieldsTest extends TestCase
{
    private const HOST = 'http://splotnikov.dev';

    public function test_every_field_carries_its_input_hints(): void
    {
        foreach (['/contact', '/nl/contact'] as $path) {
            $doc = new DOMDocument;
            @$doc->loadHTML('<?xml encoding="UTF-8">'.$this->get(self::HOST.$path)->assertOk()->getContent());
            $x = new DOMXPath($doc);
            $field = fn (string $name): DOMElement => $x->query('//form[@id="cform"]//*[@name="'.$name.'"]')->item(0) ?? $this->fail("$path: no field $name");

            $email = $field('email');
            $this->assertSame('email', $email->getAttribute('type'));
            $this->assertSame('email', $email->getAttribute('inputmode'), $path);
            $this->assertSame('email', $email->getAttribute('autocomplete'), $path);
            $this->assertSame('false', $email->getAttribute('spellcheck'), $path);
            $this->assertSame('next', $email->getAttribute('enterkeyhint'), $path);
            $this->assertSame('name', $field('name')->getAttribute('autocomplete'), $path);
            $this->assertSame('next', $field('name')->getAttribute('enterkeyhint'), $path);
            $this->assertSame('enter', $field('message')->getAttribute('enterkeyhint'), $path);
            $this->assertSame('off', $field('message')->getAttribute('autocomplete'), $path);
            $this->assertSame('off', $field('about')->getAttribute('autocomplete'), $path);
            $this->assertSame('off', $field('website')->getAttribute('autocomplete'), $path);

            foreach ($x->query('//form[@id="cform"]//input[not(@type="hidden")]|//form[@id="cform"]//select|//form[@id="cform"]//textarea') as $el) {
                /** @var DOMElement $el */
                $this->assertNotSame('', $el->getAttribute('autocomplete'), "$path: ".$el->getAttribute('name').' has no autocomplete');
            }
        }
    }
}
