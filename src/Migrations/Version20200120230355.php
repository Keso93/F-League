<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200120230355 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE performance (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, game_id INT DEFAULT NULL, player_rating DOUBLE PRECISION NOT NULL, INDEX IDX_82D7968199E6F5DF (player_id), INDEX IDX_82D79681E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, position_id INT DEFAULT NULL, location_id INT DEFAULT NULL, club_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, jmbg VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, INDEX IDX_98197A65DD842E46 (position_id), INDEX IDX_98197A6564D218E (location_id), INDEX IDX_98197A6561190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE league (id INT AUTO_INCREMENT NOT NULL, league_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, ptt INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE position (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, league_id INT DEFAULT NULL, home_club_id INT DEFAULT NULL, guest_club_id INT DEFAULT NULL, game_date DATE NOT NULL, home_goals VARCHAR(255) NOT NULL, guest_goals VARCHAR(255) NOT NULL, INDEX IDX_232B318C58AFC4DE (league_id), INDEX IDX_232B318CD439C16A (home_club_id), INDEX IDX_232B318C21DCCE7E (guest_club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE performance ADD CONSTRAINT FK_82D7968199E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE performance ADD CONSTRAINT FK_82D79681E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65DD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6564D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6561190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C58AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CD439C16A FOREIGN KEY (home_club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C21DCCE7E FOREIGN KEY (guest_club_id) REFERENCES club (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE performance DROP FOREIGN KEY FK_82D7968199E6F5DF');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C58AFC4DE');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A6564D218E');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65DD842E46');
        $this->addSql('ALTER TABLE performance DROP FOREIGN KEY FK_82D79681E48FD905');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A6561190A32');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CD439C16A');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C21DCCE7E');
        $this->addSql('DROP TABLE performance');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE league');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE club');
    }
}
