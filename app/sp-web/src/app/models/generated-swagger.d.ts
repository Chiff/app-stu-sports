export interface AuthorModel {
  email?: string;
  fullName?: string;
  id?: number;
  name?: string;
}

export interface AuthoritiesResourcesModel {
  content?: Array<AuthorityModel>;
  links?: LinkModel;
}

export interface AuthorityModel {
  authority?: string;
  id?: number;
}

export interface CaseResourceModel {
  _id?: ObjectIdModel;
  author?: AuthorModel;
  color?: string;
  creationDate?: string;
  icon?: string;
  immediateData?: FieldModel;
  lastModified?: string;
  links?: LinkModel;
  petriNetId?: string;
  petriNetObjectId: ObjectIdModel;
  processIdentifier: string;
  stringId?: string;
  title: string;
  visualId?: string;
}

export interface CaseSearchRequestModel {
  author?: AuthorModel;
  fullText?: string;
  group?: string;
  petriNet?: PetriNetModel;
  processIdentifier?: string;
  query?: string;
  role?: string;
  stringId?: string;
  title?: string;
  transition?: string;
}

export interface ChangePasswordRequestModel {
  login?: string;
  newPassword?: string;
  password?: string;
}

export interface ChangedFieldByFileFieldContainerModel {
  isSave?: boolean;
}

export interface ComponentModel {
  name?: string;
}

export interface CountResponseModel {
  count?: number;
  entity?: string;
}

export interface CreateCaseBodyModel {
  color?: string;
  netId?: string;
  title?: string;
}

export interface CreateFilterBodyModel {
  description?: string;
  query?: string;
  title?: string;
  type?: string;
  visibility?: number;
}

export interface DataFieldReferenceModel {
  petriNetId?: string;
  stringId?: string;
  title?: string;
  transitionId?: string;
  type?: string;
}

export interface DataFieldReferencesResourceModel {
  content?: Array<DataFieldReferenceModel>;
  links?: LinkModel;
}

export interface DataFieldsResourceModel {
  content?: Array<LocalisedFieldModel>;
  links?: LinkModel;
}

export interface DataGroupModel {
  alignment?: string;
  fields?: DataFieldsResourceModel;
  layout?: DataGroupLayoutModel;
  stretch?: boolean;
  title?: string;
}

export interface DataGroupLayoutModel {
  cols?: number;
  rows?: number;
  type?: string;
}

export interface DataGroupsResourceModel {
  content?: Array<DataGroupModel>;
  links?: LinkModel;
}

export interface ElasticTaskSearchRequestModel {
  _case?: TaskSearchCaseRequestModel;
  fullText?: string;
  group?: string;
  process?: string;
  query?: string;
  role?: string;
  title?: string;
  transitionId?: string;
  user?: number;
}

export interface FieldModel {
  _id?: ObjectIdModel;
  component?: ComponentModel;
  description?: I18nStringModel;
  format?: Record<string, unknown>;
  importId?: string;
  layout?: FieldLayoutModel;
  length?: number;
  name?: I18nStringModel;
  order?: number;
  placeholder?: I18nStringModel;
  stringId?: string;
  type?: FieldTypeEnum;
  value?: Record<string, unknown>;
  view?: ViewModel;
}

export enum FieldTypeEnum {
  Text = 'text',
  Date = 'date',
  Boolean = 'boolean',
  File = 'file',
  FileList = 'fileList',
  Enumeration = 'enumeration',
  EnumerationMap = 'enumeration_map',
  Multichoice = 'multichoice',
  MultichoiceMap = 'multichoice_map',
  Number = 'number',
  User = 'user',
  Tabular = 'tabular',
  CaseRef = 'caseRef',
  DateTime = 'dateTime',
  Button = 'button',
  TaskRef = 'taskRef',
}

export interface FieldLayoutModel {
  alignment?: string;
  appearance?: string;
  cols?: number;
  offset?: number;
  rows?: number;
  template?: string;
  x?: number;
  y?: number;
}

export interface GroupModel {
  childGroups?: Array<GroupModel>;
  id?: number;
  members?: Array<MemberModel>;
  name: string;
  parentGroup?: GroupModel;
}

export interface GroupsResourceModel {
  content?: Array<GroupModel>;
  links?: LinkModel;
}

export interface I18nStringModel {
  defaultValue?: string;
  key?: string;
}

export interface LinkModel {
  deprecation?: string;
  href?: string;
  hreflang?: string;
  media?: string;
  rel?: string;
  templated?: boolean;
  title?: string;
  type?: string;
}

export interface LocalisedEventOutcomeResourceModel {
  assignee?: UserModel;
  error?: string;
  finishDate?: string;
  links?: LinkModel;
  startDate?: string;
  success?: string;
}

export interface LocalisedFieldModel {
  component?: ComponentModel;
  description?: string;
  formatFilter?: Record<string, unknown>;
  layout?: FieldLayoutModel;
  length?: number;
  name?: string;
  order?: number;
  placeholder?: string;
  stringId?: string;
  type?: LocalisedFieldTypeEnum;
  value?: Record<string, unknown>;
  view?: ViewModel;
}

export enum LocalisedFieldTypeEnum {
  Text = 'text',
  Date = 'date',
  Boolean = 'boolean',
  File = 'file',
  FileList = 'fileList',
  Enumeration = 'enumeration',
  EnumerationMap = 'enumeration_map',
  Multichoice = 'multichoice',
  MultichoiceMap = 'multichoice_map',
  Number = 'number',
  User = 'user',
  Tabular = 'tabular',
  CaseRef = 'caseRef',
  DateTime = 'dateTime',
  Button = 'button',
  TaskRef = 'taskRef',
}

export interface LocalisedFilterResourceModel {
  author?: AuthorModel;
  created?: string;
  description?: string;
  links?: LinkModel;
  mergeOperation?: LocalisedFilterResourceMergeOperationEnum;
  query?: string;
  stringId?: string;
  title?: string;
  type?: string;
  visibility?: number;
}

export enum LocalisedFilterResourceMergeOperationEnum {
  AND = 'AND',
  OR = 'OR',
}

export interface LocalisedTaskResourceModel {
  assignPolicy?: string;
  assignTitle?: string;
  cancelTitle?: string;
  caseColor?: string;
  caseId?: string;
  caseTitle?: string;
  dataFocusPolicy?: string;
  delegateTitle?: string;
  finishDate?: string;
  finishPolicy?: string;
  finishTitle?: string;
  finishedBy?: number;
  icon?: string;
  immediateData?: FieldModel;
  layout?: TaskLayoutModel;
  links?: LinkModel;
  priority?: number;
  requiredFilled?: boolean;
  startDate?: string;
  stringId?: string;
  title?: string;
  transactionId?: string;
  transitionId?: string;
  user?: UserModel;
}

export interface MemberModel {
  email: string;
  groups?: Array<GroupModel>;
  id?: number;
  name: string;
  surname: string;
  userId?: number;
}

export interface MessageResourceModel {
  data?: string;
  error?: string;
  links?: LinkModel;
  success?: string;
}

export interface NewUserRequestModel {
  email?: string;
  groups?: Array<number>;
  processRoles?: Array<string>;
}

export interface ObjectIdModel {
  counter?: number;
  date?: string;
  machineIdentifier?: number;
  processIdentifier?: number;
  time?: number;
  timeSecond?: number;
  timestamp?: number;
}

export interface PetriNetModel {
  identifier?: string;
}

export interface PetriNetReferenceModel {
  author?: AuthorModel;
  createdDate?: string;
  defaultCaseName?: string;
  icon?: string;
  identifier?: string;
  immediateData?: DataFieldReferenceModel;
  initials?: string;
  stringId?: string;
  title?: string;
  version?: string;
}

export interface PetriNetReferenceResourceModel {
  author?: AuthorModel;
  createdDate?: string;
  defaultCaseName?: string;
  icon?: string;
  identifier?: string;
  immediateData?: DataFieldReferenceModel;
  initials?: string;
  links?: LinkModel;
  stringId?: string;
  title?: string;
  version?: string;
}

export interface PetriNetReferenceResourcesModel {
  content?: Array<PetriNetReferenceResourceModel>;
  links?: LinkModel;
}

export interface PetriNetReferenceWithMessageResourceModel {
  data?: string;
  error?: string;
  links?: LinkModel;
  net?: PetriNetReferenceModel;
  success?: string;
}

export interface PreferencesModel {
  locale?: string;
  userId?: number;
}

export interface PreferencesResourceModel {
  links?: LinkModel;
  locale?: string;
  userId?: number;
}

export interface ProcessRoleModel {
  description?: string;
  importId?: string;
  name?: string;
  netImportId?: string;
  netStringId?: string;
  netVersion?: string;
  stringId?: string;
}

export interface ProcessRoleResourceModel {
  description?: string;
  links?: LinkModel;
  name?: string;
  stringId?: string;
}

export interface ProcessRolesResourceModel {
  content?: Array<ProcessRoleResourceModel>;
  links?: LinkModel;
}

export interface RegistrationRequestModel {
  name?: string;
  password?: string;
  surname?: string;
  token?: string;
}

export interface ResourceModel {
  description?: string;
  filename?: string;
  open?: boolean;
  readable?: boolean;
}

export interface SingleCaseSearchRequestAsListModel {
  list?: CaseSearchRequestModel;
}

export interface SingleElasticTaskSearchRequestAsListModel {
  list?: ElasticTaskSearchRequestModel;
}

export interface SingleTaskSearchRequestAsListModel {
  list?: TaskSearchRequestModel;
}

export interface TaskLayoutModel {
  cols?: number;
  fieldAlignment?: string;
  offset?: number;
  rows?: number;
  type?: string;
}

export interface TaskReferenceModel {
  stringId?: string;
  title?: string;
  transitionId?: string;
}

export interface TaskSearchCaseRequestModel {
  id?: string;
  title?: string;
}

export interface TaskSearchRequestModel {
  _case?: TaskSearchCaseRequestModel;
  fullText?: string;
  group?: string;
  process?: string;
  role?: string;
  title?: string;
  transitionId?: string;
  user?: number;
}

export interface TransactionResourceModel {
  links?: LinkModel;
  title?: string;
  transitions?: string;
}

export interface TransactionsResourceModel {
  content?: Array<TransactionResourceModel>;
  links?: LinkModel;
}

export interface TransitionReferenceModel {
  immediateData?: DataFieldReferenceModel;
  petriNetId?: string;
  stringId?: string;
  title?: string;
}

export interface TransitionReferencesResourceModel {
  content?: Array<TransitionReferenceModel>;
  links?: LinkModel;
}

export interface UpdateUserRequestModel {
  avatar?: string;
  name?: string;
  surname?: string;
  telNumber?: string;
}

export interface UserModel {
  authorities?: Array<AuthorityModel>;
  avatar?: string;
  email?: string;
  fullName?: string;
  groups?: Array<GroupModel>;
  id?: number;
  name?: string;
  nextGroups?: Array<string>;
  processRoles?: Array<ProcessRoleModel>;
  surname?: string;
  telNumber?: string;
}

export interface UserResourceModel {
  authorities?: Array<AuthorityModel>;
  avatar?: string;
  email?: string;
  fullName?: string;
  groups?: Array<GroupModel>;
  id?: number;
  links?: LinkModel;
  name?: string;
  nextGroups?: Array<string>;
  processRoles?: Array<ProcessRoleModel>;
  surname?: string;
  telNumber?: string;
}

export interface UserSearchRequestBodyModel {
  fulltext?: string;
  roles?: string;
}

export interface ViewModel {
  value?: string;
}
