<?php

namespace App\Service\Generator\Framework;


use App\Entity\ApiEntity;
use App\Service\Generator\AbstractFrameworkGenerator;

class SymfonyGenerator extends AbstractFrameworkGenerator
{
    /**
     * Generates the project.
     *
     * @return AbstractFrameworkGenerator
     */
    protected function generateProject(): AbstractFrameworkGenerator
    {
        $this->bash(sprintf('composer create-project symfony/skeleton %s', $this->api->getName()), $this->projectPath);
        $this->apiPath = sprintf('%s/%s', $this->projectPath, $this->api->getName());

        $this->bash('composer require api', $this->apiPath);

        return $this;
    }

    /**
     * Generates the entities.
     *
     * @return AbstractFrameworkGenerator
     */
    protected function generateEntities(): AbstractFrameworkGenerator
    {
        foreach ($this->api->getEntities() as $entity) {
            $this->generateEntity($entity);
            $this->generateRepository($entity);
        }

        return $this;
    }

    /**
     * Generates an entity.
     *
     * @param ApiEntity $entity
     * @return SymfonyGenerator
     */
    private function generateEntity(ApiEntity $entity): SymfonyGenerator
    {
        $context = [
            'name' => ucfirst($entity->getName()),
            'attributes' => [],
        ];

        foreach ($entity->getFields() as $field) {
            $context['attributes'][$field->getId()] = [
                'name' => $field->getName(),
                'type' => $field->getType(),
                'nullable' => (bool) $field->getNullable() ? 'true' : 'false',
            ];

            if ($field->getType() === 'string') {
                $context['attributes'][$field->getId()]['extra'] = ', length=255';
            }
        }
        $render = $this->renderFile('symfony/entity', $context);

        $filename = sprintf('%s.php', ucfirst($entity->getName()));
        $this->createFile('src/Entity', $filename, $render);
        return $this;
    }

    /**
     * Generates a repository.
     */
    public function generateRepository(ApiEntity $entity)
    {
        $classname = sprintf('%sRepository', ucfirst($entity->getName()));
        $context = [
            'name' => $classname,
            'entityName' => ucfirst($entity->getName())
        ];

        $render = $this->renderFile('symfony/repository', $context);

        $this->createFile('src/Repository', sprintf('%s.php', $classname), $render);
    }

    /**
     * Runs a Symfony command <php bin/console $command>
     *
     * @param string $command
     * @return string
     */
    private function symfonyCommand(string $command): string
    {
        return $this->bash(sprintf('php bin/console %s', $command), $this->apiPath);
    }
}