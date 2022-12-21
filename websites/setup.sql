CREATE DATABASE IF NOT EXISTS job;
USE job;

DROP TABLE IF EXISTS applicant;

CREATE TABLE applicant (
    id INT(11) AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(45) DEFAULT NULL, 
    email VARCHAR(45) DEFAULT NULL, 
    details LONGBLOB, 
    jobId INT(11) DEFAULT NULL, 
    cv VARCHAR(255) DEFAULT NULL
);

DROP TABLE IF EXISTS category;
CREATE TABLE category (
    id INT(11) AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(45) DEFAULT NULL
);

INSERT INTO category (name) VALUES ('IT'), ('Human Resources'), ('Sales');

DROP TABLE IF EXISTS account;
CREATE TABLE account (
    id INT(11) AUTO_INCREMENT PRIMARY KEY, 
    username VARCHAR(255) NOT NULL, 
    password VARCHAR(255) NOT NULL, 
    isAdmin BOOLEAN DEFAULT TRUE
);

-- Insert default admin account. Password is 'letmein'
INSERT INTO account (username, password) VALUES ('admin', '$2y$10$arD3MUTYfY4CwuS8X7pDmOmNWAA2F3342OQmqAU8ejD.2QfM12CLS');
-- Insert test client/customer account. Password is 'letmein'
INSERT INTO account (isAdmin, username, password) VALUES (false, 'testclient', '$2y$10$arD3MUTYfY4CwuS8X7pDmOmNWAA2F3342OQmqAU8ejD.2QfM12CLS');
-- Insert test staff/admin account. Password is 'letmein'
INSERT INTO account (isAdmin, username, password) VALUES (true, 'teststaff', '$2y$10$arD3MUTYfY4CwuS8X7pDmOmNWAA2F3342OQmqAU8ejD.2QfM12CLS');

DROP TABLE IF EXISTS job;
CREATE TABLE job (
    id INT(11) AUTO_INCREMENT PRIMARY KEY, 
    title VARCHAR(45) DEFAULT NULL, 
    description LONGBLOB, 
    salary VARCHAR(45) DEFAULT NULL, 
    closingDate DATE DEFAULT NULL, 
    categoryId INT(11) DEFAULT NULL, 
    location VARCHAR(255) DEFAULT NULL, 
    archived BOOLEAN DEFAULT FALSE, 
    accountId INT(11) DEFAULT NULL
);

INSERT INTO job VALUES (3, 'First level tech support', 'Job overview:\r\n\r\nTo work alongside the IT field based team in one of our acute Hospital sites. This team provides high quality equipment installation, technical support and advisory services for EKHUFT staff. They proactively manage incidents and requests, accepting ownership, evaluating, resolving and enabling the rapid resolution of a broad range of issues. This will include the testing and implementing of new and replacement hardware and appropriate software and the resolving of malfunctions. They look to achieve high standards of customer service and delivery of maximum business benefits.\r\n\r\nMain responsibilities:\r\n\r\n\r\n    To analyse incidents and deliver technical resolutions as part of the IT support\r\n\r\n    Service, to contribute to an efficient and effective IT service desk.\r\n\r\n    Review tickets within Service Management systems using established priorities.\r\n\r\n    Man the helpdesk telephone on a daily basis to resolve issues for end users.\r\n\r\n    Learn what notes and updates should be done on tickets.\r\n\r\n    The initial investigation and resolution of incidents relating to business and\r\n\r\n    desktop applications, and subsequent referral to either senior support analysts, to\r\n\r\n    2nd/ 3rd line support, the application management team or a 3rd party.\r\n\r\n    Learn to deliver end user introductory training on IT systems.\r\n\r\n    Undertakes daily operational checks as defined and trained by the wider team.\r\n\r\n    Participate in projects as required.\r\n\r\n    Support tracking of IT assets (software and hardware) using software tools.\r\n\r\n    Setup workstations and laptops for new starters\r\n\r\n    Support with physical desk moves between locations\r\n\r\n    Deploy and install software to computers\r\n\r\n    Perform password resets and help end users with profile and connectivity issues.\r\n\r\n    Allow and remove access to folders and email distribution lists\r\n\r\n    Perform basic proactive tasks for backups and learn how to restore backups.\r\n\r\n    Carry out any other duties as required by the IT Management Team.', '£15, 000 - £18, 000', '2023-04-09', 1, 'Northampton', 0, 1), (4, 'IT Infrastructure Manager', 'About the role\r\n\r\nAs an experienced IT Infrastructure Manager, you will work closely with the Head of IT to design and deliver a robust, secure, and flexible IT solution.\r\n\r\nTaking day-to-day responsibility for the smooth running of the IT systems, you will ensure that full business continuity plans are in place for our IT systems and services.\r\n\r\nYou will work closely with the Park Services and Events teams to ensure that appropriate access to IT services, including CCTV, is available throughout the Parks.', '£45, 000 - £58, 000', '2023-05-15', 1, 'Northampton', 0, 3), (5, 'Sales Assistant', 'Our client is an award winning sales and marketing organisation; who are looking to enhance their sales team with independent individuals who are capable of seeking and developing new opportunities within the sales and marketing industry.\r\n\r\nWithin this opportunity you will be working alongside the best sales and marketing specialists, promoting an exciting client portfolio. You will represent iconic brands and play a very important role in ongoing business success while developing your skills in residential environments. This opportunity will provide high rewards both career wise, and financially.\r\n\r\nThe successful candidate will be a well-presented, self-starter capable of demonstrating a desire to succeed in a sales environment.\r\n\r\nSuccessful candidates will:\r\n\r\n    Have strong communication skills\r\n    Be self-motivated\r\n    Possess an impeccable work ethic\r\n    Have a tenacious approach to personal development\r\n    Possess a competitive sales mentality\r\n    Have an entrepreneurial mind-set\r\n    Team work\r\n\r\nIf you can demonstrate the qualities as set out above and believe that you have the ability to develop new business, they would like to hear from you!\r\n\r\nNo experience is necessary although our client welcomes candidates with any previous experience in the following areas: customer service, sales representative, marketing supervisor, sales executive, direct sales, field sales, marketing executive, retail, service supervisor, call centre, call centre inbound, marketing representative, manager, bar manager, hospitality, receptionist, warehouse, marketing assistant, front of house, direct marketing, sales assistant, and any other customer service or sales role. This is a self employed commission only opportunity with the ability to create your own future.', '£12, 000 - £15, 000', '2023-05-29', 3, 'Northampton', 0, 3), (6, 'HR Manager', 'HR Manager: An ambitious HR Manager is required to help deliver an effective and comprehensive Human Resource service to a growing organisation with fully-funded plans to double in size over the next 18 months.\r\n\r\nWorking in a consultative manner, the successful HR Manager will work on a standalone basis to ensure quality advice and support is provided as part of the journey to make the organisation an industry leading \"Employer of Choice\".\r\n\r\nThis exciting new role would ideally suit an ambitious generalist HR professional eager to take on a dynamic position offering genuine career progression opportunities.\r\n\r\nKey Responsibilities\r\n\r\n    Provide HR support and advice to management on company HR policies and procedures, including employment law advice e.g. disciplinary, grievance, performance.\r\n    Provide high-quality recruitment and selection service to all departments including the use of social media.\r\n    Develop and implement HR policy and practice, contract templates, HR documentation and HR database developments, ensuring that all are up to date with UK legislation.\r\n    Provide ongoing employee relations support and advice to whole firm relating to contractual and general HR matters.\r\n    Review compensation and benefit plans e.g. salary review, bonus plan and other non-specified benefit plans.\r\n    Propose and advise on internal and external training for employees.\r\n    Create career path models to include job descriptions, person specs and competency models for all roles to support individuals\' career progression.\r\n    Manage the HR calendar: performance reviews, salary reviews, development planning, ensuring these processes support the ongoing strategic growth plan.\r\n    Develop the organisation culture to ensure \"Employer of Choice\" status is attained through determining the current culture, proposing organisation initiatives and then implementing after approval to achieve \"EOC\" tag.', '£35, 000 - £40, 000', '2023-05-29', 2, 'Northampton', 0, 2);
