<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240826154517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video_progress ADD user_id INT DEFAULT NULL, ADD video_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE video_progress ADD CONSTRAINT FK_8A83C0FAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE video_progress ADD CONSTRAINT FK_8A83C0FA29C1004E FOREIGN KEY (video_id) REFERENCES video (id)');
        $this->addSql('CREATE INDEX IDX_8A83C0FAA76ED395 ON video_progress (user_id)');
        $this->addSql('CREATE INDEX IDX_8A83C0FA29C1004E ON video_progress (video_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video_progress DROP FOREIGN KEY FK_8A83C0FAA76ED395');
        $this->addSql('ALTER TABLE video_progress DROP FOREIGN KEY FK_8A83C0FA29C1004E');
        $this->addSql('DROP INDEX IDX_8A83C0FAA76ED395 ON video_progress');
        $this->addSql('DROP INDEX IDX_8A83C0FA29C1004E ON video_progress');
        $this->addSql('ALTER TABLE video_progress DROP user_id, DROP video_id');
    }
}
