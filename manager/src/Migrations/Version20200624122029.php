<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200624122029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE work_projects_project_memberships (id UUID NOT NULL, project_id UUID NOT NULL, member_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6884CF98166D1F9C ON work_projects_project_memberships (project_id)');
        $this->addSql('CREATE INDEX IDX_6884CF987597D3FE ON work_projects_project_memberships (member_id)');
        $this->addSql('COMMENT ON COLUMN work_projects_project_memberships.project_id IS \'(DC2Type:work_projects_project_id)\'');
        $this->addSql('COMMENT ON COLUMN work_projects_project_memberships.member_id IS \'(DC2Type:work_members_member_id)\'');
        $this->addSql('CREATE TABLE work_projects_project_memberships_department (membership_id UUID NOT NULL, department_id UUID NOT NULL, PRIMARY KEY(membership_id, department_id))');
        $this->addSql('CREATE INDEX IDX_7C81BE821FB354CD ON work_projects_project_memberships_department (membership_id)');
        $this->addSql('CREATE INDEX IDX_7C81BE82AE80F5DF ON work_projects_project_memberships_department (department_id)');
        $this->addSql('COMMENT ON COLUMN work_projects_project_memberships_department.department_id IS \'(DC2Type:work_projects_project_department_id)\'');
        $this->addSql('CREATE TABLE work_projects_project_memberships_roles (membereship_id UUID NOT NULL, role_id UUID NOT NULL, PRIMARY KEY(membereship_id, role_id))');
        $this->addSql('CREATE INDEX IDX_8289928B69AFC95C ON work_projects_project_memberships_roles (membereship_id)');
        $this->addSql('CREATE INDEX IDX_8289928BD60322AC ON work_projects_project_memberships_roles (role_id)');
        $this->addSql('COMMENT ON COLUMN work_projects_project_memberships_roles.role_id IS \'(DC2Type:work_projects_role_id)\'');
        $this->addSql('ALTER TABLE work_projects_project_memberships ADD CONSTRAINT FK_6884CF98166D1F9C FOREIGN KEY (project_id) REFERENCES work_projects_projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_memberships ADD CONSTRAINT FK_6884CF987597D3FE FOREIGN KEY (member_id) REFERENCES work_members_members (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_memberships_department ADD CONSTRAINT FK_7C81BE821FB354CD FOREIGN KEY (membership_id) REFERENCES work_projects_project_memberships (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_memberships_department ADD CONSTRAINT FK_7C81BE82AE80F5DF FOREIGN KEY (department_id) REFERENCES work_projects_project_departments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_memberships_roles ADD CONSTRAINT FK_8289928B69AFC95C FOREIGN KEY (membereship_id) REFERENCES work_projects_project_memberships (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_projects_project_memberships_roles ADD CONSTRAINT FK_8289928BD60322AC FOREIGN KEY (role_id) REFERENCES work_projects_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE work_projects_project_memberships_department DROP CONSTRAINT FK_7C81BE821FB354CD');
        $this->addSql('ALTER TABLE work_projects_project_memberships_roles DROP CONSTRAINT FK_8289928B69AFC95C');
        $this->addSql('DROP TABLE work_projects_project_memberships');
        $this->addSql('DROP TABLE work_projects_project_memberships_department');
        $this->addSql('DROP TABLE work_projects_project_memberships_roles');
    }
}
