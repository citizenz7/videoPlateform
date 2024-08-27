<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240826154305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video_progress_user DROP FOREIGN KEY FK_8660D5B0A76ED395');
        $this->addSql('ALTER TABLE video_progress_user DROP FOREIGN KEY FK_8660D5B061593668');
        $this->addSql('DROP TABLE video_progress_user');
        $this->addSql('ALTER TABLE video_progress DROP FOREIGN KEY FK_8A83C0FA29C1004E');
        $this->addSql('DROP INDEX IDX_8A83C0FA29C1004E ON video_progress');
        $this->addSql('ALTER TABLE video_progress DROP video_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE video_progress_user (video_progress_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8660D5B061593668 (video_progress_id), INDEX IDX_8660D5B0A76ED395 (user_id), PRIMARY KEY(video_progress_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE video_progress_user ADD CONSTRAINT FK_8660D5B0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_progress_user ADD CONSTRAINT FK_8660D5B061593668 FOREIGN KEY (video_progress_id) REFERENCES video_progress (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_progress ADD video_id INT NOT NULL');
        $this->addSql('ALTER TABLE video_progress ADD CONSTRAINT FK_8A83C0FA29C1004E FOREIGN KEY (video_id) REFERENCES video (id)');
        $this->addSql('CREATE INDEX IDX_8A83C0FA29C1004E ON video_progress (video_id)');
    }
}
