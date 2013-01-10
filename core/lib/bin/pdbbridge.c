/*

php ntlm authentication library
Version 1.2
verifyntlm.c - verifies NTLM credentials against samba using pdbedit

Copyright (c) 2012-2013 Gabriele Rossetti

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.


Prerequisites:
- pdbedit (samba) - user database must be store locally
- libssl (openssl)


To install, compile and set the sticky bit:
# gcc verifyntlm.c -lssl -o pdbbridge
# chown root pdbbridge
# chmod u=rwxs,g=x,o=x pdbbridge

Move the binary to a location such as /sbin/
# mv pdbbridge /sbin


For more, see http://siphon9.net/loune/2010/12/php-ntlm-integration-with-samba/


*/

#include <unistd.h>
#include <sys/wait.h>
#include <sys/types.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <ctype.h>

#include <openssl/hmac.h>
#include <openssl/evp.h>

#include <iconv.h>

#define PDBEDIT_PATH "/usr/bin/pdbedit"


/*****************************************************************************/
/*                     print user info with groups                           */ 
/*****************************************************************************/
int get_uinfo(int argc, char** argv)
{
  /* arguments check */
  if (argc < 3) {
    printf("usage: %s get_uinfo user_id\n", argv[0]);
    exit(-1);
  }
  
  get_ulist(argv[2]);
}


/*****************************************************************************/
/*                     print the domain's users list                         */ 
/*****************************************************************************/
int get_ulist(char * user_id)
{
  pid_t pid;
  int fd[2];
  pipe(fd);
  pid=fork();

  /* child code */
  if(pid == 0) {
    
    /* Redirect stdout into writing end of pipe */
    dup2(fd[1], STDOUT_FILENO);

    /* closes pipes ends caus we've already copied */
    close(fd[0]);
    close(fd[1]);

    /* executable */
    execl(PDBEDIT_PATH, "pdbedit", "-L",  NULL);

    /* Shouldn't reach here */
    exit(1);
  }

  /* Parent code */
  else if(pid > 0) {
    int status;
    build_ulist(fd, user_id);

    /* Waits for the child to quit so we don't leave a zombie */
    wait(&status);
    return(0);
  }
}

int build_ulist(int *fd, char * filter_id)
{
  char buff[2];
  buff[1] = '\0';
  int i = 0;
  int colon = 0;
  int invalid = 0;
  char user_id[100];
  char user_name[100];
  char user_info[1000];
  user_info[0] = '\0';
  
  /* closes unused pipes ends */
  close(fd[1]);

  /* reads childs stdout, removes computer name lines and output results */    
  while(read(fd[0], buff,1)){
    /* sets as invalid if it's a computer account */
    if(buff[0] == '$') invalid = 1;
    if(buff[0] == ':') {
      colon++;
      i = 0;
      read(fd[0], buff,1);
    }

    /* if EOL resets indexes */
    if(buff[0] == '\n') {
      /* if a filter is set returns only the matches user id */
      if(strlen(filter_id) != 0) {
        if(strcmp(user_id, filter_id)) invalid = 1;
      }

      /* if is not a computer account output the line, then reset pointers */
      if(!invalid) {
        strcat(user_info, user_id);
        strcat(user_info, ":");
        strcat(user_info, user_name);
        strcat(user_info, ":");
        get_user_group(user_id, &user_info);
        user_info[strlen(user_info)-1] = '\0';
        printf("%s\n", user_info); 
      }
      user_info[0] = '\0';
      colon = 0;
      i = 0;
      invalid = 0;
    }

    /* copy stream depending by the current field */
    else {
      switch(colon) {
        case 0 :
          user_id[i]   = buff[0];
          user_id[i+1] = '\0';
          break;
  
        case 2 :
          user_name[i] = buff[0];
          user_name[i+1] = '\0';
          break;
      }

      i++;
    }
  }

  /* closes remaining pipes ends */   
  close(fd[0]);
}



/*****************************************************************************/
/*                     prints the groups specified user sits                 */ 
/*****************************************************************************/
get_user_group(char * user_id, char * user_info)
{
  pid_t pid;
  int fd[2];
  pipe(fd);
  pid=fork();

  /* child code */
  if(pid == 0) {
    int uidlen = strlen(user_id);
    char rule1[uidlen+2];
    char rule2[uidlen+2];
    char rule3[uidlen+2];
    char rule4[uidlen+2];
  
    strcpy(rule1, ",");
    strcpy(rule2, ":");
    strcpy(rule3, ":");
    strcpy(rule4, ",");
  
    strcat(rule1, user_id);
    strcat(rule2, user_id);
    strcat(rule3, user_id);
    strcat(rule4, user_id);
  
    strcat(rule1, "$");
    strcat(rule2, "$");
    strcat(rule3, ",");
    strcat(rule4, ",");
    
    /* Redirect stdout into writing end of pipe */
    dup2(fd[1], STDOUT_FILENO);

    /* closes pipes ends caus we've already copied */
    close(fd[0]);
    close(fd[1]);

    /* executable */
    execl("/bin/grep", "grep",
          "-e", rule1,
          "-e", rule2,
          "-e", rule3,
          "-e", rule4,
          "/etc/group", NULL );

    /* Shouldn't reach here */
    exit(1);
  }
  
  /* Parent code */
  else if(pid > 0) {
    int status;
    build_uglist(&fd, user_info);

    /* Waits for the child to quit so we don't leave a zombie */
    wait(&status);
    return(0);
  }  
}


build_uglist(int *fd, char * user_info)
{         
  char buff[2];
  buff[1] = '\0';
  int i = 0;
  int colon = 0;
  char gid[1000];
  
  /* closes unused pipes ends */
  close(fd[1]);

  /* reads childs stdout, removes computer name lines and output results */    
  while(read(fd[0], buff,1)){
    /* field marker */
    if(buff[0] == ':') {
      colon++;
      i = 0;
    }

    /* if EOL resets indexes */
    if(buff[0] == '\n') {      
      strcat(user_info, gid);
      colon = 0;
      i = 0;
    }
    
    /* copy stream depending by the current field */
    else if(colon == 0) {
      gid[i]   = buff[0];
      gid[i+1] = ',';
      gid[i+2] = '\0';
      i++;
    }

  }

  /* closes remaining pipes ends */   
  close(fd[0]);
}

/*****************************************************************************/
/*             checks if the given user/password hash pair match             */ 
/*****************************************************************************/
int check_pass(int argc, char** argv)
{
  /* arguments check */
  if (argc < 4) {
    printf("usage: %s check_pass user_name password_hash\n", argv[0]);
    exit(-1);
  }

  pid_t pid;
  int fd[2];
  pipe(fd);
  pid=fork();

  /* child code */
  if(pid == 0) {
    int er;
    
    /* Redirect stdout into writing end of pipe */
    dup2(fd[1], STDOUT_FILENO);
    dup2(er, STDERR_FILENO);
    
    /* closes pipes ends caus we've already copied */
    close(fd[0]);
    close(fd[1]);
    
    /* executable */
    execl(PDBEDIT_PATH, "pdbedit", "-w", argv[2], NULL);

    /* Shouldn't reach here */
    exit(1);
  }
  
  /* Parent code */
  else if(pid > 0) {
    int status;
    char buff[1];
    int i = 0;
    int colon = 0;
    char hash[33];
    
    /* closes unused pipes ends */
    close(fd[1]);
    
    /* reads childs stdout, removes computer name lines and output results */    
    while(read(fd[0], buff,1)){
      if(buff[0] == ':') {
        colon++;
        i = 0;
      }
   
      /* copy stream depending by the current field */
      else if(colon == 3) {
        hash[i]   = buff[0];
        hash[i+1] = '\0';
        i++;
      }  
    }

    /* closes remaining pipes ends */   
    close(fd[0]);

    /* Waits for the child to quit so we don't leave a zombie */
    wait(&status);

    if(status != 0){ printf("error");}
    if(!strcmp(hash, argv[3])){ printf("true");}
    else {printf("false");}
    
    return(0);
  }    
}

int main(int argc, char** argv)
{
  /* SUID Check */
  if (geteuid() != 0) {
    printf(
        "SUID root needed. Please set the sticky bit and correct "
        "permissions for %s.\n"
        "    ie:\n"
        "    # chown root %s\n"
        "    # chmod u=rwxs,g=x,o=x %s\n", argv[0], argv[0], argv[0]);
    exit(-1);
  }

  /* pdbedit check */
  if (access(PDBEDIT_PATH, F_OK ) == -1) {
    printf("%s not found. Please install samba or change the"
           " PDBEDIT_PATH constant.\n",
           PDBEDIT_PATH);
    exit(-1);
  }

  /* arguments check */
  if (argc < 2) {
    printf("usage: %s command [[option1] [option2] ...]\n"
           "string arguments are expected to be in UTF16LE\n"
           "all arguments are hex encoded\n"
           "prints 1 if successful, otherwise 0\n", argv[0]);
    exit(-1);
  }


  if (!strcmp(argv[1], "get_uinfo"))
    get_uinfo(argc, argv);
  else if (!strcmp(argv[1], "get_ulist"))
    get_ulist("");
  else if (!strcmp(argv[1], "check_pass"))
    check_pass(argc, argv);
}





