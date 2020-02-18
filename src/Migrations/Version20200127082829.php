<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200127082829 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game CHANGE home_club_id home_club_id INT DEFAULT NULL, CHANGE guest_club_id guest_club_id INT DEFAULT NULL, CHANGE league_id league_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE performance CHANGE player_id player_id INT DEFAULT NULL, CHANGE game_id game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player CHANGE location_id location_id INT DEFAULT NULL, CHANGE club_id club_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE game CHANGE league_id league_id INT DEFAULT NULL, CHANGE home_club_id home_club_id INT DEFAULT NULL, CHANGE guest_club_id guest_club_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE performance CHANGE player_id player_id INT DEFAULT NULL, CHANGE game_id game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player CHANGE location_id location_id INT DEFAULT NULL, CHANGE club_id club_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
