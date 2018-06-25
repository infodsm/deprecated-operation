#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#define MAX_NUM 10
#define MAX_LEN 20

void addPhoneNum(int *count, char phoneNumberBook[MAX_NUM][2][MAX_LEN]);
void delPhoneNum(int *count, char phoneNumberBook[MAX_NUM][2][MAX_LEN]);
void printAllPhoneNum(int *count, char phoneNumberBook[MAX_NUM][2][MAX_LEN]);
void editPhoneNum(int *count, char phoneNumberBook[MAX_NUM][2][MAX_LEN]);

int main() {
    int select = 0;
    int count = 0;
    char phoneNumberBook[MAX_NUM][2][MAX_LEN] = {0};

    while (1) {
        printf("원하는 작업을 선택하세요 (1 : 추가, 2 : 삭제, 3 : 모두 보기, 4 "
               ": 수정, 5 : 끝내기) >> ");
        scanf("%d", &select);

        switch (select) {
        case 1:
            if (count < MAX_NUM) {
                addPhoneNum(&count, phoneNumberBook);
            } else {
                printf("전화번호부가 꽉 찼습니다.\n");
            }
            break;
        case 2:
            if (count > 0) {
                delPhoneNum(&count, phoneNumberBook);
            } else {
                printf("전화번호부가 비었습니다.\n");
            }
            break;
        case 3:
            printAllPhoneNum(&count, phoneNumberBook);
            break;
        case 4:
            editPhoneNum(&count, phoneNumberBook);
            break;
        case 5:
            printf("프로그램을 종료합니다.\n");
            exit(0);
        default:
            printf("잘못 입력하셨습니다.\n");
        }
    }
}

void addPhoneNum(int *count, char phoneNumberBook[MAX_NUM][2][MAX_LEN]) {
    printf("이름을 입력하세요 >> ");
    scanf("%s", phoneNumberBook[*count][0]);
    printf("전화번호를 입력하세요 >> ");
    scanf("%s", phoneNumberBook[*count][1]);

    *count = *count + 1;
    return;
}

void delPhoneNum(int *count, char phoneNumberBook[MAX_NUM][2][MAX_LEN]) {
    int number = 0;
    int i = 0;

    printf("삭제할 번호를 입력하세요 >> ");
    scanf("%d", &number);
    if ((number > 0) && (number <= *count)) {
        for (i = number; i < MAX_NUM; i++) {
            strcpy(phoneNumberBook[i - 1][0], phoneNumberBook[i][0]);
            strcpy(phoneNumberBook[i - 1][1], phoneNumberBook[i][1]);
        }

        printf("삭제되었습니다.\n");
        *count = *count - 1;
        return;
    } else {
        printf("없는 번호입니다.\n");
        return;
    }
}

void printAllPhoneNum(int *count, char phoneNumberBook[MAX_NUM][2][MAX_LEN]) {
    int i = 0;
    for (i = 0; i < *count; i++) {
        printf("%d번 %s %s\n", i + 1, phoneNumberBook[i][0],
               phoneNumberBook[i][1]);
    }
    return;
}

void editPhoneNum(int *count, char phoneNumberBook[MAX_NUM][2][MAX_LEN]) {
    int number = 0;
    int i = 0;

    printf("수정할 번호를 입력하세요 >> ");
    scanf("%d", &number);
    if ((number > 0) && (number <= *count)) {
        printf("이름을 입력하세요 >> ");
        scanf("%s", phoneNumberBook[number - 1][0]);
        printf("전화번호를 입력하세요 >> ");
        scanf("%s", phoneNumberBook[number - 1][1]);

        printf("수정되었습니다.\n");
        return;
    } else {
        printf("없는 번호입니다.\n");
        return;
    }
}
