<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\Form\FormInterface;

abstract class AbstractController extends BaseAbstractController
{
    public function getFormErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $name => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $name => $child) {
            if ($child->isSubmitted() && $child->isValid()) {
                continue;
            }

            $child->count() > 0 && $errors[$name] = $this->getFormErrors($child);

            foreach ($child->getErrors() as $error) {
                $errors[$name][] = $error->getMessage();
            }
        }

        return $errors;
    }
}
