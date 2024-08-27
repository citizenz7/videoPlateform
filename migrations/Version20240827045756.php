<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827045756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_videos_watched (user_id INT NOT NULL, video_id INT NOT NULL, INDEX IDX_95D12ED2A76ED395 (user_id), INDEX IDX_95D12ED229C1004E (video_id), PRIMARY KEY(user_id, video_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_videos_watched ADD CONSTRAINT FK_95D12ED2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_videos_watched ADD CONSTRAINT FK_95D12ED229C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_videos_watched DROP FOREIGN KEY FK_95D12ED2A76ED395');
        $this->addSql('ALTER TABLE user_videos_watched DROP FOREIGN KEY FK_95D12ED229C1004E');
        $this->addSql('DROP TABLE user_videos_watched');
    }
}
