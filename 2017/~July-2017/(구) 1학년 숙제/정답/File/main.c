#include <stdio.h>
#include <string.h>
#define MAX_LEN 100
#define FILE_NAME "text.txt"

int addText(char *str);
int deleteText(int index);
int showAllText();
int editText(int index, char *editStr);

int main() {
        int select = 0, index = 0;
        char str[MAX_LEN] = {0};
        char *p;

        while(1) {
                printf("원하는 작업을 선택하세요 (1 : 추가, 2 : 삭제, 3 : 보기, 4 : 수정, 5 : 끝내기) >> ");
                scanf("%d", &select);
                while (getchar() != '\n') ;
                switch (select) {
                case 1:
                        printf("텍스트 입력 >> ");
                        fgets(str, sizeof(str)-1, stdin);
                        if((p=strchr(str, '\n')) != NULL ) *p = '\0';
                        addText(str);
                        break;
                case 2:
                        printf("번호 입력 >> ");
                        scanf("%d", &index);
                        while (getchar() != '\n') ;
                        deleteText(index);
                        break;
                case 3:
                        showAllText();
                        break;
                case 4:
                        printf("번호 입력 >> ");
                        scanf("%d", &index);
                        while (getchar() != '\n') ;
                        printf("텍스트 입력 >> ");
                        fgets(str, sizeof(str)-1, stdin);
                        if((p=strchr(str, '\n')) != NULL ) *p = '\0';
                        editText(index, str);
                        break;
                case 5:
                        printf("프로그램을 종료합니다.\n");
                        exit(0);
                        break;
                default:
                        printf("잘못 입력하셨습니다.\n");
                }
        }
}

int addText(char *str) {
        FILE *fp;
        fp = fopen(FILE_NAME, "a");
        if (fp == NULL) {
                printf("파일 열기 오류!\n");
                return 1;
        }

        fprintf(fp, "%s\n", str);

        fclose(fp);
        return 0;
}

int deleteText(int index) {
        char str[MAX_LEN];
        int cnt = 1;

        FILE *fp, *tfp;
        fp = fopen(FILE_NAME, "r");
        tfp = fopen("temp", "w");
        if (fp == NULL) {
                printf("파일 열기 오류!\n");
                return 1;
        }
        if (tfp == NULL) {
                printf("파일 열기 오류!\n");
                return 1;
        }

        while(fscanf(fp, "%s", str) != EOF) {
                if (cnt == index) {
                        continue;
                }
                fprintf(tfp, "%s\n", str);
                cnt++;
        }
        fclose(fp);
        fclose(tfp);

        if(remove(FILE_NAME) != 0) {
                printf("파일 삭제 실패!\n");
        }
        if(rename("temp", FILE_NAME) != 0) {
                printf("파일 이름 변경 실패!\n");
        }
        return 0;
}

int showAllText() {
        FILE *fp;
        fp = fopen(FILE_NAME, "r");
        if (fp == NULL) {
                printf("파일 열기 오류!\n");
                return 1;
        }
        int cnt = 1;
        char str[MAX_LEN];
        while (fscanf(fp, "%s", str) != EOF) {
                printf("%d. %s\n", cnt, str);
                cnt++;
        }

        fclose(fp);
        return 0;
}

int editText(int index, char *editStr) {
        char str[MAX_LEN];
        int cnt = 1;

        FILE *fp, *tfp;
        fp = fopen(FILE_NAME, "r");
        tfp = fopen("temp", "w");
        if (fp == NULL) {
                printf("파일 열기 오류!\n");
                return 1;
        }
        if (tfp == NULL) {
                printf("파일 열기 오류!\n");
                return 1;
        }

        while(fscanf(fp, "%s", str) != EOF) {
                if (cnt == index) {
                        strcpy(str, editStr);
                }
                fprintf(tfp, "%s\n", str);
                cnt++;
        }
        fclose(fp);
        fclose(tfp);

        if(remove(FILE_NAME) != 0) {
                printf("파일 삭제 실패!\n");
        }
        if(rename("temp", FILE_NAME) != 0) {
                printf("파일 이름 변경 실패!\n");
        }
        return 0;
}
