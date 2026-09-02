// The two reference checklist templates shipped with the product. Idempotent by design
// (findOrCreate, never overwrite) — called both by `npm run seed:demo` (full local/demo
// seed: templates + a demo org/customer/assessment) and automatically right after the
// very first auditor account is created (see routes/auth.js bootstrap-admin), so any
// fresh deployment — including `docker compose up` — has real reference content without
// a manual step, and re-running never clobbers content the user has since edited.
import { readFileSync } from 'fs';
import { fileURLToPath } from 'url';
import path from 'path';
import { ChecklistTemplate } from '../models/ChecklistTemplate.js';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

// ---------------------------------------------------------------------------
// Template A — a straight lift from database/panaceainfosec_full.sql's `questionnaire`
// table (service_id=1, PCI DSS, status=active): 139 real audit questions (one obviously
// erroneous row — id 100, text literally "ISO" — was dropped as a data-entry error, not
// a question). Extracted once via a one-off parsing script into scripts/data/panacea-pci-
// questions.json (controlRef = `PANACEA-{legacy id}` for traceability back to the dump);
// responseType was inferred per-row (short "...?" -> yes_no_na, "Provide..." ->
// file_required, else long_text) since the legacy schema had no response-type concept.
// ---------------------------------------------------------------------------
function legacyLiftSections() {
  const raw = readFileSync(
    path.join(__dirname, '../../scripts/data/panacea-pci-questions.json'),
    'utf8'
  );
  const questions = JSON.parse(raw).map((q, i) => ({
    text: q.text,
    controlRef: q.controlRef,
    responseType: q.responseType,
    required: true,
    order: i,
  }));
  return [{ title: 'PCI DSS Assessment Questionnaire (migrated from Panacea)', order: 0, questions }];
}

// ---------------------------------------------------------------------------
// Template B — a genuine, comprehensive PCI DSS v4.0 checklist, organised by the 12
// principal requirements. Condensed to requirement-level controls (not full ROC testing
// procedures, which would run into the thousands) — capped well under 200 questions so
// it stays walkable in a demo.
// ---------------------------------------------------------------------------
const RT = { yn: 'yes_no_na', text: 'short_text', long: 'long_text', file: 'file_required' };

const PCI_SECTIONS = [
  {
    title: 'Req 1 — Install and Maintain Network Security Controls',
    questions: [
      ['1.1.1', 'Are network security control (NSC) configuration standards documented and kept current?', RT.file],
      ['1.2.1', 'Is there an accurate, current network diagram showing all connections between the CDE and other networks?', RT.file],
      ['1.2.2', 'Is there an accurate, current data-flow diagram showing all account data flows across systems and networks?', RT.file],
      ['1.2.5', 'Are all services, protocols, and ports allowed inbound/outbound identified, justified, and documented?', RT.yn],
      ['1.2.6', 'Are insecure services, protocols, and ports identified, with security features documented and implemented for each?', RT.yn],
      ['1.2.7', 'Are NSC configurations reviewed at least once every six months to confirm they remain accurate?', RT.yn],
      ['1.3.1', 'Is inbound traffic to the CDE restricted to only that which is necessary?', RT.yn],
      ['1.3.2', 'Is outbound traffic from the CDE restricted to only that which is necessary?', RT.yn],
      ['1.4.1', 'Are NSCs in place between all wireless networks and the CDE?', RT.yn],
      ['1.4.4', 'Are systems that store account data prevented from being directly accessible from untrusted networks?', RT.yn],
      ['1.5.1', 'Are security controls implemented on computing devices that connect to both untrusted networks and the CDE?', RT.yn],
      ['1.5.1-note', 'Note any exceptions or compensating controls for this requirement, if applicable.', RT.long],
    ],
  },
  {
    title: 'Req 2 — Apply Secure Configurations to All System Components',
    questions: [
      ['2.1.1', 'Are configuration standards developed, applied, and maintained for all system components?', RT.file],
      ['2.2.1', 'Are vendor-supplied defaults always changed before installing a system on the network (passwords, SNMP strings, etc.)?', RT.yn],
      ['2.2.2', 'Are unnecessary default accounts removed or disabled before a system is installed on the network?', RT.yn],
      ['2.2.4', 'Are only necessary services, protocols, daemons, and functions enabled on each system component?', RT.yn],
      ['2.2.5', 'If insecure services/protocols/daemons are present, is business justification documented and additional security features implemented?', RT.yn],
      ['2.2.6', 'Are system security parameters configured to prevent misuse?', RT.yn],
      ['2.2.7', 'Is all non-console administrative access encrypted using strong cryptography?', RT.yn],
      ['2.3.1', 'For wireless environments, are vendor defaults changed at installation (encryption keys, passwords, SNMP community strings)?', RT.yn],
      ['2.3.2', 'Are wireless encryption keys changed whenever anyone with knowledge of the keys leaves the organisation or changes role?', RT.yn],
      ['2.2.1-evidence', 'Upload the current system hardening / configuration standard document.', RT.file],
      ['2.2.1-notes', 'Describe any deviations from the documented hardening standard and their compensating controls.', RT.long],
      ['2.2.1-owner', 'Who owns and maintains the configuration standards (name/role)?', RT.text],
    ],
  },
  {
    title: 'Req 3 — Protect Stored Account Data',
    questions: [
      ['3.2.1', 'Is account data storage kept to a minimum per documented data-retention and disposal policies?', RT.file],
      ['3.3.1', 'Is sensitive authentication data (SAD) not stored after authorization, even if encrypted?', RT.yn],
      ['3.4.1', 'Is the primary account number (PAN) rendered unreadable anywhere it is stored (masking, hashing, truncation, or strong cryptography)?', RT.yn],
      ['3.4.2', 'Is remote access technology configured to prevent copy/relocate of PAN except for authorised, documented business need?', RT.yn],
      ['3.5.1', 'Is PAN protected wherever it is stored, using one of the approved rendering methods?', RT.yn],
      ['3.5.1.1', 'If disk-level or partition-level encryption is used, is it implemented only as allowed for removable media, or supplemented with another mechanism?', RT.yn],
      ['3.6.1', 'Are procedures in place to protect cryptographic keys used to protect stored account data against disclosure and misuse?', RT.yn],
      ['3.6.1.1', 'Are cryptographic keys generated and stored with the strongest key-management practices (dual control, split knowledge)?', RT.yn],
      ['3.7.1', 'Are key-management policies and procedures documented and implemented for keys used for protection of stored account data?', RT.file],
      ['3.7.4', 'Are cryptographic keys retired/replaced when their integrity has been weakened, or is known/suspected to be compromised?', RT.yn],
      ['3.3.1-evidence', 'Upload evidence of the data-retention and disposal policy.', RT.file],
      ['3.4.1-scope', 'Describe where PAN is stored across the environment (databases, files, logs, backups).', RT.long],
      ['3.5.1-method', 'Which rendering method(s) are used to protect stored PAN?', RT.text],
      ['3.7.1-owner', 'Who owns the cryptographic key-management process (name/role)?', RT.text],
    ],
  },
  {
    title: 'Req 4 — Protect Cardholder Data with Strong Cryptography During Transmission',
    questions: [
      ['4.1.1', 'Are processes and mechanisms for protecting cardholder data with strong cryptography during transmission documented?', RT.file],
      ['4.2.1', 'Is strong cryptography and security protocols used to safeguard PAN during transmission over open, public networks?', RT.yn],
      ['4.2.1.1', 'Is an inventory maintained of trusted keys/certificates used to protect PAN during transmission?', RT.file],
      ['4.2.1.2', 'Are wireless networks transmitting PAN or connected to the CDE using industry best-practice encryption?', RT.yn],
      ['4.2.2', 'Is PAN secured with strong cryptography whenever it is sent via end-user messaging technologies?', RT.yn],
      ['4.2.2-notes', 'List any messaging channels (email, chat, SMS) that may carry PAN and how they are secured.', RT.long],
      ['4.2.1-scope', 'Describe the external transmission points for PAN (e.g. payment gateway, third-party processor).', RT.long],
      ['4.1.1-owner', 'Who owns the transmission-security policy (name/role)?', RT.text],
    ],
  },
  {
    title: 'Req 5 — Protect All Systems and Networks from Malicious Software',
    questions: [
      ['5.1.1', 'Are processes and mechanisms for protecting systems against malware documented and in use?', RT.file],
      ['5.2.1', 'Is anti-malware software deployed on all system components, except where documented as not at risk?', RT.yn],
      ['5.2.2', 'Does the anti-malware solution detect, remove, and protect against all known types of malware?', RT.yn],
      ['5.2.3', 'For systems not at risk from malware, is a periodic risk evaluation performed to confirm this remains true?', RT.yn],
      ['5.3.1', 'Is anti-malware software kept current via automatic updates?', RT.yn],
      ['5.3.2', 'Does the anti-malware solution perform periodic and/or continuous scans, or continuous behavioural analysis?', RT.yn],
      ['5.3.3', 'For removable electronic media, is the anti-malware solution configured to scan on insertion/connection?', RT.yn],
      ['5.3.4', 'Are anti-malware audit logs generated and retained per the log-retention requirements?', RT.yn],
      ['5.4.1', 'Are anti-phishing mechanisms in place to protect personnel against phishing attacks?', RT.yn],
      ['5.2.1-evidence', 'Upload an export/screenshot showing anti-malware deployment status across the estate.', RT.file],
    ],
  },
  {
    title: 'Req 6 — Develop and Maintain Secure Systems and Software',
    questions: [
      ['6.2.1', 'Are bespoke and custom software developed securely, based on industry standards and/or best practices?', RT.file],
      ['6.2.2', 'Are software development personnel trained at least once every 12 months on secure coding techniques?', RT.yn],
      ['6.2.3', 'Is bespoke/custom software reviewed prior to release to identify and correct coding vulnerabilities?', RT.yn],
      ['6.2.4', 'Are software engineering techniques or other methods used to prevent or mitigate common software attacks?', RT.yn],
      ['6.3.1', 'Are security vulnerabilities identified and managed via a documented, risk-ranked process?', RT.file],
      ['6.3.2', 'Is an inventory of bespoke and custom software maintained to facilitate vulnerability and patch management?', RT.file],
      ['6.3.3', 'Are applicable security patches/updates installed within one month of release for critical vulnerabilities?', RT.yn],
      ['6.4.1', 'For public-facing web applications, are threats and vulnerabilities addressed continually via automated tools or manual review?', RT.yn],
      ['6.4.2', 'Is an automated technical solution (e.g. WAF) deployed to detect and prevent web-based attacks on public-facing web applications?', RT.yn],
      ['6.4.3', 'Are all payment-page scripts loaded and executed in the consumer browser managed and authorized?', RT.yn],
      ['6.5.1', 'Are changes to system components made according to a documented change-control process?', RT.file],
      ['6.5.2', 'Upon completion of a significant change, are applicable PCI DSS requirements confirmed to be in place?', RT.yn],
      ['6.5.3', 'Are pre-production environments separated from production environments with access control enforced?', RT.yn],
      ['6.5.4', 'Are roles and functions separated between production and pre-production environments?', RT.yn],
      ['6.5.5', 'Is live PAN not used for testing or development, except where those environments are secured to the same PCI DSS level?', RT.yn],
      ['6.3.3-notes', 'Describe the patch-management SLA and how critical/high vulnerabilities are tracked to closure.', RT.long],
    ],
  },
  {
    title: 'Req 7 — Restrict Access to System Components and Cardholder Data by Business Need to Know',
    questions: [
      ['7.1.1', 'Are policies and procedures for restricting access to system components and cardholder data documented?', RT.file],
      ['7.2.1', 'Is an access-control model defined that includes appropriate assignment of privileges based on job classification?', RT.file],
      ['7.2.2', 'Is access assigned to users, including privileged users, based on job classification and function (least privilege)?', RT.yn],
      ['7.2.3', 'Are all access assignments approved by authorized personnel and documented?', RT.yn],
      ['7.2.4', 'Are all user accounts and related access privileges reviewed at least once every six months?', RT.yn],
      ['7.2.5', 'Are application and system accounts, and related access privileges, assigned and managed appropriately?', RT.yn],
      ['7.2.6', 'Is all user access to query repositories of stored cardholder data restricted?', RT.yn],
      ['7.3.1', 'Is an access-control system in place that restricts access based on a user’s need to know?', RT.yn],
      ['7.3.2', 'Is the access-control system configured to enforce permissions assigned to individuals/applications/processes?', RT.yn],
      ['7.3.3', 'Is the access-control system set to "deny all" by default unless specifically allowed?', RT.yn],
    ],
  },
  {
    title: 'Req 8 — Identify Users and Authenticate Access to System Components',
    questions: [
      ['8.1.1', 'Are processes and mechanisms for identifying users and authenticating access documented and in use?', RT.file],
      ['8.2.1', 'Are all users assigned a unique ID before access to system components or cardholder data is allowed?', RT.yn],
      ['8.2.2', 'Are group, shared, or generic accounts (or authentication methods) prohibited except in documented exceptions?', RT.yn],
      ['8.2.4', 'Are additions, deletions, and modifications of user IDs, authentication factors and other identifier objects managed via approval and documentation?', RT.yn],
      ['8.2.5', 'Are access privileges for terminated users immediately revoked?', RT.yn],
      ['8.2.6', 'Are inactive user accounts removed or disabled within 90 days of inactivity?', RT.yn],
      ['8.2.7', 'Are accounts used by third parties to access, support, or maintain systems enabled only during the period needed and monitored?', RT.yn],
      ['8.2.8', 'When a user session has been idle for more than 15 minutes, is re-authentication required?', RT.yn],
      ['8.3.1', 'Is strong authentication (something you know/have/are) required for all access into the CDE?', RT.yn],
      ['8.3.6', 'Do password/passphrase requirements meet minimum length and complexity standards (12+ chars, numeric and alpha)?', RT.yn],
      ['8.3.9', 'Are passwords/passphrases changed at least once every 90 days, OR is the security posture of accounts analysed dynamically?', RT.yn],
      ['8.4.1', 'Is multi-factor authentication (MFA) implemented for all non-console access into the CDE for personnel with administrative access?', RT.yn],
      ['8.4.2', 'Is MFA implemented for all access into the CDE?', RT.yn],
      ['8.4.3', 'Is MFA implemented for all remote network access originating from outside the entity’s network?', RT.yn],
      ['8.5.1', 'Is MFA implemented in a way that cannot be bypassed by any user, including administrative users?', RT.yn],
      ['8.6.1', 'If accounts used by systems/applications can be used for interactive login, are they managed with additional oversight?', RT.yn],
    ],
  },
  {
    title: 'Req 9 — Restrict Physical Access to Cardholder Data',
    questions: [
      ['9.1.1', 'Are processes and mechanisms for restricting physical access to cardholder data documented and in use?', RT.file],
      ['9.2.1', 'Are appropriate facility entry controls in place to limit and monitor physical access to systems in the CDE?', RT.yn],
      ['9.2.2', 'Are physical/logical controls implemented to restrict access to publicly accessible network jacks?', RT.yn],
      ['9.2.3', 'Is physical access to wireless access points, gateways, networking/communications hardware controlled?', RT.yn],
      ['9.2.4', 'Is physical access to consoles in sensitive areas restricted via locking when not in use?', RT.yn],
      ['9.3.1', 'Are procedures in place to authorize and manage physical access of personnel to the CDE?', RT.yn],
      ['9.3.4', 'Is a visitor log used to maintain a physical record of visitor activity, retained for at least three months?', RT.yn],
      ['9.4.1', 'Are all media with cardholder data physically secured?', RT.yn],
      ['9.4.2', 'Is external distribution of media with cardholder data classified and sent by a secured, tracked delivery method?', RT.yn],
      ['9.4.6', 'Is hard-copy materials containing cardholder data destroyed when no longer needed (cross-cut shred, incinerate, or pulp)?', RT.yn],
      ['9.4.7', 'Are electronic media with cardholder data destroyed when no longer needed (secure wipe, degauss, or physical destruction)?', RT.yn],
      ['9.5.1', 'Are point-of-interaction (POI) devices protected from tampering and unauthorized substitution?', RT.yn],
    ],
  },
  {
    title: 'Req 10 — Log and Monitor All Access to System Components and Cardholder Data',
    questions: [
      ['10.1.1', 'Are processes and mechanisms for logging and monitoring all access to system components documented and in use?', RT.file],
      ['10.2.1', 'Are audit logs enabled and active for all system components and cardholder data?', RT.yn],
      ['10.2.1.1', 'Do audit logs capture all individual user access to cardholder data?', RT.yn],
      ['10.2.1.2', 'Do audit logs capture all actions taken by any individual with administrative access?', RT.yn],
      ['10.2.1.4', 'Do audit logs capture all invalid logical access attempts?', RT.yn],
      ['10.2.1.6', 'Do audit logs capture the initialization, stopping, or pausing of audit logs?', RT.yn],
      ['10.2.2', 'Do audit logs record at minimum: user ID, event type, date/time, success/failure, origination, affected data/component/resource?', RT.yn],
      ['10.3.1', 'Is read access to audit log files limited to those with a job-related need?', RT.yn],
      ['10.3.3', 'Are audit log files promptly backed up to a centralized log server or media difficult to alter?', RT.yn],
      ['10.3.4', 'Is file-integrity monitoring or change-detection software used on audit logs to alert on unauthorized modification?', RT.yn],
      ['10.4.1', 'Are logs for all system components reviewed at least once daily (automated or manual)?', RT.yn],
      ['10.4.1.1', 'Is automated mechanism used to perform audit-log reviews?', RT.yn],
      ['10.5.1', 'Is audit-log history retained for at least 12 months, with the most recent 3 months immediately available?', RT.yn],
      ['10.6.1', 'Is time-synchronization technology used to synchronize all system clocks?', RT.yn],
      ['10.7.1', 'For service providers/POI merchants: is failure of critical security-control systems responded to promptly?', RT.yn],
      ['10.4.1-notes', 'Describe how log review findings are escalated and tracked (SIEM, ticketing, alerting).', RT.long],
    ],
  },
  {
    title: 'Req 11 — Test Security of Systems and Networks Regularly',
    questions: [
      ['11.1.1', 'Are processes and mechanisms for regularly testing security of systems and networks documented and in use?', RT.file],
      ['11.2.1', 'Are authorized and unauthorized wireless access points managed, with a documented inventory maintained?', RT.yn],
      ['11.3.1', 'Are internal vulnerability scans performed at least once every three months, with high-risk vulnerabilities resolved and rescanned?', RT.file],
      ['11.3.1.1', 'Are all other applicable vulnerabilities (not ranked as high-risk) managed per a documented risk-based methodology?', RT.yn],
      ['11.3.2', 'Are external vulnerability scans performed at least once every three months by a PCI SSC Approved Scanning Vendor (ASV)?', RT.file],
      ['11.4.1', 'Is a penetration-testing methodology defined, documented, and implemented, based on industry-accepted approaches?', RT.file],
      ['11.4.2', 'Is internal penetration testing performed at least once every 12 months, and after significant infrastructure/application changes?', RT.yn],
      ['11.4.3', 'Is external penetration testing performed at least once every 12 months, and after significant changes?', RT.yn],
      ['11.4.4', 'Are exploitable vulnerabilities found during penetration testing corrected, with testing repeated to verify?', RT.yn],
      ['11.5.1', 'Is an intrusion-detection and/or intrusion-prevention technique used to detect and/or prevent intrusions into the network?', RT.yn],
      ['11.5.1.1', 'For service providers: is a change-detection mechanism deployed on critical file servers/systems, with alerts investigated?', RT.yn],
      ['11.6.1', 'Is a change- and tamper-detection mechanism deployed on payment pages to alert on unauthorized modification?', RT.yn],
    ],
  },
  {
    title: 'Req 12 — Support Information Security with Organizational Policies and Programs',
    questions: [
      ['12.1.1', 'Is an overall information-security policy established, published, maintained, and disseminated to all relevant personnel?', RT.file],
      ['12.1.2', 'Is the information-security policy reviewed at least once every 12 months and updated as needed?', RT.yn],
      ['12.2.1', 'Is an acceptable-use policy for end-user technologies documented and implemented?', RT.file],
      ['12.3.1', 'For each PCI DSS requirement with flexibility in how frequently it is performed, is a targeted risk analysis documented?', RT.yn],
      ['12.3.3', 'Are cryptographic cipher suites and protocols in use documented and reviewed at least once every 12 months?', RT.yn],
      ['12.3.4', 'Are hardware and software technologies in use reviewed at least once every 12 months to confirm vendor support?', RT.yn],
      ['12.4.1', 'For service providers: is executive management responsibility established for protection of cardholder data?', RT.yn],
      ['12.5.1', 'Is an inventory of system components in scope for PCI DSS maintained and kept current?', RT.file],
      ['12.5.2', 'Is PCI DSS scope documented and confirmed accurate at least once every 12 months, and upon significant change?', RT.yn],
      ['12.6.1', 'Is a formal security-awareness program in place to make all personnel aware of the entity’s information-security policy?', RT.yn],
      ['12.6.2', 'Is the security-awareness program reviewed at least once every 12 months and updated as needed?', RT.yn],
      ['12.6.3', 'Do personnel receive security-awareness training upon hire and at least once every 12 months?', RT.yn],
      ['12.7.1', 'Are personnel screened prior to hire to minimize risk of insider attacks on cardholder data (within local law constraints)?', RT.yn],
      ['12.8.1', 'Is a list of all third-party service providers (TPSPs) with whom account data is shared maintained?', RT.file],
      ['12.8.2', 'Is a written agreement maintained with each TPSP acknowledging their responsibility for the account data they possess?', RT.yn],
      ['12.8.3', 'Is a process in place to engage TPSPs, including proper due diligence prior to engagement?', RT.yn],
      ['12.9.1', 'For service providers: is written acknowledgement provided to customers of responsibility for the security of account data?', RT.yn],
      ['12.10.1', 'Is an incident-response plan documented and ready to be activated in the event of a suspected or confirmed breach?', RT.file],
      ['12.10.4', 'Are personnel with incident-response responsibilities trained and periodically tested?', RT.yn],
      ['12.10.5', 'Does the incident-response plan include monitoring and responding to alerts from security-monitoring systems (IDS/IPS, FIM, DLP)?', RT.yn],
    ],
  },
];

function pciSections() {
  return PCI_SECTIONS.map((section, sIdx) => ({
    title: section.title,
    order: sIdx,
    questions: section.questions.map(([controlRef, text, responseType], qIdx) => ({
      text,
      controlRef,
      responseType,
      required: true,
      order: qIdx,
    })),
  }));
}

export const REFERENCE_TEMPLATES = [
  {
    name: 'Legacy PCI DSS Migration (Panacea)',
    category: 'Payment Security — Legacy',
    sections: legacyLiftSections,
  },
  {
    name: 'PCI DSS v4.0 Comprehensive Checklist',
    category: 'Payment Security',
    sections: pciSections,
  },
];

// Idempotent: never touches a template that already exists (by name), so a template the
// user has since edited, renamed its questions, or deactivated is left completely alone.
export async function seedReferenceTemplates(auditorId) {
  const created = [];
  for (const def of REFERENCE_TEMPLATES) {
    const existing = await ChecklistTemplate.findOne({ name: def.name });
    if (existing) continue;
    const template = await ChecklistTemplate.create({
      name: def.name,
      category: def.category,
      status: 'active',
      sections: def.sections(),
      createdBy: auditorId,
    });
    created.push(template);
  }
  return created;
}
