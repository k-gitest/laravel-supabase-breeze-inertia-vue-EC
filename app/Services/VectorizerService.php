<?php

namespace App\Services;

use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\StopWords\English;
use meCab\meCab;
use App\Models\Product;
use App\Models\ProductVector;
use Log;

class VectorizerService
{
    protected $vectorizer;
    protected $transformer;
    protected $mecab;

    public function __construct()
    {
        $this->vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer(), new English());
        $this->transformer = new TfIdfTransformer();
        $this->mecab = new meCab();
        $this->fixedDimensions = 500;
        $this->stopwords = ['これ', 'は', 'です', 'の', 'を', 'が', 'に', 'と', 'も', 'し', 'ます', 'た', 'り', 'ます', 'た', 'な', 'の', 'で', 'は', 'ない', 'か', 'と', 'いう', 'こと', 'です', '。', '、', '「', '」', '（', '）'];
    }

    public function transform($texts)
    {
        $products = $this->getProducts();
        $corpus = $this->createCorpus($products);
        $vectorizedCorpus = $this->vectorizeCorpus($corpus);
        $this->saveVectors($vectorizedCorpus, $products);
    }

    protected function getProducts()
    {
        return Product::select('id', 'name', 'description')->get();
    }

    protected function createCorpus($products)
    {
        $corpus = [];
        $corpusMap = [];

        foreach ($products as $index => $product) {
            $tokens = $this->tokenizeText($product->description);
            $corpus[] = implode(' ', $tokens);
        }

        return $corpus;
    }

    protected function tokenizeText($text)
    {
        $parsedText = $this->mecab->analysis($text);
        $tokens = [];

        foreach ($parsedText as $word) {
            $text = $word->getText();
            if ($this->isValidToken($text)) {
                $tokens[] = $text;
            }
        }

        return $tokens;
    }

    protected function isValidToken($token)
    {
        return preg_match('/^[\p{L}\p{N}ー々〆〤]+$/u', $token) && !in_array($token, $this->stopwords);
    }

    protected function vectorizeCorpus($corpus)
    {
        $this->vectorizer->fit($corpus);
        $this->vectorizer->transform($corpus);
        $this->transformer->fit($corpus);
        $this->transformer->transform($corpus);

        return $this->padVectors($corpus);
    }

    protected function padVectors($vectors)
    {
        return array_map(function ($vector) {
            if (count($vector) < $this->fixedDimensions) {
                return array_pad($vector, $this->fixedDimensions, 0);
            }
            return array_slice($vector, 0, $this->fixedDimensions);
        }, $vectors);
    }

    protected function saveVectors($vectors, $products)
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        
        foreach ($products as $index => $product) {
            $result = $this->saveOrUpdateVector($product->id, 'description', $vectors[$index]);
            if ($result === true) {
                $successCount++;
            } else {
                $errorCount++;
                $errors[] = $result;
            }
        }

        $this->logResults($successCount, $errorCount, $errors);
    }

    protected function saveOrUpdateVector($productId, $type, $embedding)
    {
        try {
            $productVector = ProductVector::firstOrNew([
                'product_id' => $productId,
                'type' => $type
            ]);
    
            $productVector->embedding = $embedding;
            $productVector->save();
            return true;
        } catch (\Exception $e) {
            echo "Error saving data for product_id: {$productId}. Error: " . $e->getMessage() . "\n";
        }
    }

    protected function logResults($successCount, $errorCount, $errors)
    {
        Log::info("Vector save operation completed. Successes: $successCount, Errors: $errorCount");

        if ($errorCount > 0) {
            Log::error("Errors occurred during vector save operation:", $errors);
        }
    }
}
